<?php
session_start();
require_once 'checkToken.php';
if (isset($_SESSION['client'])) {
    header('Location: ../index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="../styles/style.css" type="text/css">
  <title>Nouveau Client</title>
  <style>
    .erreur { display: block; color: red; }
    .succes { color: green; }
    .echec { color: red; }
  </style>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script>
  $(document).ready(function() {
      let emailExiste = false;
      let emailVerifie = false;
      let messageEmail = "Vérification de l'adresse email en attente.";
      let timerEmail;
      let versionEmail = 0;
      let envoiEnCours = false;

      $("#formulaire input:not([type='submit']):not([type='hidden'])").each(function() {
          $(this).after('<span class="erreur"></span>');
      });

      function verifier(champ, valide, message) {
          let erreur = champ.next(".erreur");
          if (valide) {
              champ.css("background-color", "#ccffcc");
              erreur.text("");
              return true;
          } else {
              champ.css("background-color", "#ffcccc");
              erreur.text(message);
              return false;
          }
      }

      function verifierNom() {
          return verifier($("#n"), $("#n").val().trim() !== "", "Le nom est obligatoire.");
      }

      function verifierPrenom() {
          return verifier($("#p"), $("#p").val().trim() !== "", "Le prénom est obligatoire.");
      }

      function verifierAdresse() {
          return verifier($("#adr"), $("#adr").val().trim() !== "", "L'adresse est obligatoire.");
      }

      function verifierTelephone() {
          return verifier($("#num"), $("#num").val().trim() !== "", "Le numéro est obligatoire.");
      }

      function verifierEmail() {
          let email = $("#mail").val().trim();
          let format = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
          if (!format.test(email)) {
              return verifier($("#mail"), false, "L'adresse email est invalide ou vide.");
          }
          if (!emailVerifie) {
              return verifier($("#mail"), false, messageEmail);
          }
          return verifier($("#mail"), !emailExiste, "Cette adresse email existe déjà.");
      }

      function verifierMdp() {
          let format = /^(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[^a-zA-Z0-9\s])\S+$/;
          return verifier($("#mdp1"), format.test($("#mdp1").val()),
              "Il faut une lettre, un chiffre et un caractère spécial, sans espace.");
      }

      function verifierConfirmation() {
          let mdp1 = $("#mdp1").val();
          let mdp2 = $("#mdp2").val();
          return verifier($("#mdp2"), mdp2 !== "" && mdp1 === mdp2,
              "Les mots de passe ne correspondent pas.");
      }

      function verifierFormulaire() {
          let valide = verifierNom() & verifierPrenom() & verifierAdresse() &
              verifierTelephone() & verifierEmail() & verifierMdp() & verifierConfirmation();
          $("#enregistrer").prop("disabled", !valide || envoiEnCours);
      }

      function verifierEmailAjax(version) {
          let email = $("#mail").val().trim();
          let format = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
          if (!format.test(email)) {
              return;
          }
          $.ajax({
              url: "check_email.php",
              type: "POST",
              data: { mail: email },
              dataType: "json",
              success: function(reponse) {
                  if (version !== versionEmail) {
                      return;
                  }
                  emailExiste = reponse.existe;
                  emailVerifie = true;
                  verifierFormulaire();
              },
              error: function() {
                  if (version !== versionEmail) {
                      return;
                  }
                  emailVerifie = false;
                  messageEmail = "Vérification impossible. Modifiez ou ressaisissez l'adresse email.";
                  verifierFormulaire();
              }
          });
      }

      $("#n, #p, #adr, #num, #mdp1, #mdp2").on("input", function() {
          verifierFormulaire();
      });

      $("#mail").on("input", function() {
          emailExiste = false;
          emailVerifie = false;
          messageEmail = "Vérification de l'adresse email en attente.";
          versionEmail++;
          let version = versionEmail;
          clearTimeout(timerEmail);
          verifierFormulaire();
          timerEmail = setTimeout(function() {
              verifierEmailAjax(version);
          }, 500);
      });

      $("#formulaire").on("submit", function(e) {
          e.preventDefault();
          verifierFormulaire();
          if ($("#enregistrer").prop("disabled")) {
              return;
          }
          envoiEnCours = true;
          $("#enregistrer").prop("disabled", true);
          $("#message").removeClass("succes echec").text("");
          $.ajax({
              url: "enregistrement_ajax.php",
              type: "POST",
              data: $(this).serialize(),
              dataType: "json",
              success: function(reponse) {
                  if (reponse.succes) {
                      $("#message").removeClass("echec").addClass("succes").text(reponse.message);
                      $("#formulaire :input").prop("disabled", true);
                      setTimeout(function() {
                          window.location.href = "../index.php";
                      }, 1000);
                  } else {
                      $("#message").removeClass("succes").addClass("echec").text(reponse.message);
                      envoiEnCours = false;
                      verifierFormulaire();
                  }
              },
              error: function() {
                  $("#message").removeClass("succes").addClass("echec")
                      .text("Erreur lors de l'enregistrement.");
                  envoiEnCours = false;
                  verifierFormulaire();
              }
          });
      });

      $("#enregistrer").prop("disabled", true);
  });
  </script>
</head>
<body>
  <h1>Créer un compte client</h1>

  <form id="formulaire" action="enregistrement_ajax.php" method="POST" autocomplete="off" novalidate>
    <input type="hidden" name="token" value="<?php echo getToken(); ?>">
    <p>Nom : <input type="text" id="n" name="n" value=""></p>
    <p>Prénom : <input type="text" id="p" name="p" value=""></p>
    <p>Adresse : <input type="text" id="adr" name="adr" value=""></p>
    <p>Numéro de téléphone : <input type="text" id="num" name="num" value=""></p>
    <p>Adresse e-mail : <input type="text" id="mail" name="mail" value=""></p>
    <p>Mot de passe : <input type="password" id="mdp1" name="mdp1" value=""></p>
    <p>Confirmer votre mot de passe : <input type="password" id="mdp2" name="mdp2" value=""></p>
    <p><input type="submit" id="enregistrer" value="Enregistrer" disabled></p>
  </form>

  <p id="message" role="status"></p>
  <br>
  <a href="../index.php">Retour</a>
</body>
</html>