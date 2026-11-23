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
  <title>Connexion</title>
  <style>
    .succes { color: green; }
    .echec { color: red; }
  </style>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script>
  $(document).ready(function() {
      let envoiEnCours = false;
      $("#formulaire").on("submit", function(e) {
          e.preventDefault();
          if (envoiEnCours) {
              return;
          }
          $("#message").removeClass("succes echec").text("");
          if ($("#mail").val().trim() === "" || $("#mdp").val() === "") {
              $("#message").addClass("echec").text("L'adresse email et le mot de passe sont obligatoires.");
              return;
          }
          envoiEnCours = true;
          $("#connecter").prop("disabled", true);
          $.ajax({
              url: "connecter.php",
              type: "POST",
              data: $(this).serialize(),
              dataType: "json",
              success: function(reponse) {
                  if (reponse.succes) {
                      $("#message").addClass("succes").text(reponse.message);
                      $("#formulaire :input").prop("disabled", true);
                      setTimeout(function() {
                          window.location.href = "../index.php";
                      }, 1000);
                  } else {
                      $("#message").addClass("echec").text(reponse.message);
                      envoiEnCours = false;
                      $("#connecter").prop("disabled", false);
                  }
              },
              error: function() {
                  $("#message").addClass("echec").text("Erreur lors de la connexion.");
                  envoiEnCours = false;
                  $("#connecter").prop("disabled", false);
              }
          });
      });
  });
  </script>
</head>
<body>
  <h1>Se connecter</h1>

  <form id="formulaire" action="connecter.php" method="post">
    <input type="hidden" name="token" value="<?php echo getToken(); ?>">
    <p>
      <label for="mail">Adresse e-mail :</label>
      <input type="email" id="mail" name="mail" required>
    </p>
    <p>
      <label for="mdp">Mot de passe :</label>
      <input type="password" id="mdp" name="mdp" required>
    </p>
    <p><input type="submit" id="connecter" value="Se connecter"></p>
  </form>

  <p id="message" role="status"></p>
  <br>
  <a href="nouveau.php">Nouveau client ? Créer un compte</a>
</body>
</html>