<?php
session_start();
include 'php/bd.php';
require_once 'php/checkToken.php';
$bdd = getBD();
if (isset($_SESSION['client'])) {
    nettoyerPanierTampon($bdd, $_SESSION['client']['id_client']);
}
$noms = "";
$images = "";
$quantites = "";
$prix = "";

$rep = $bdd->query('SELECT * FROM articles');
while ($ligne = $rep->fetch()) {
    $noms .= '<td><strong><a href="articles/article.php?id_art=' . $ligne['id_art'] . '">' . htmlspecialchars($ligne['nom']) . '</a></strong></td>';
    $images .= '<td><a href="articles/article.php?id_art=' . $ligne['id_art'] . '"><img src="' . htmlspecialchars($ligne['url_photo']) . '" alt="' . htmlspecialchars($ligne['nom']) . '" width="120"></a></td>';
    $quantites .= '<td>' . $ligne['quantite'] . '</td>';
    $prix .= '<td>' . $ligne['prix'] . ' €</td>';
}

$rep->closeCursor();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="styles/style.css" type="text/css" media="screen">
  <title>Accueil - Phone Universe</title>
</head>
<body>

  <div class="header-container">
    <h1>Phone Store</h1>
    
    <div class="entete-utilisateur">
      <?php if (!isset($_SESSION['client'])) { ?>
          <a href="php/nouveau.php">Nouveau Client</a> | 
          <a href="php/connexion.php">Se connecter</a>
      <?php } else { ?>
          <div class="texte-bonjour">Bonjour <?php echo htmlspecialchars($_SESSION['client']['prenom']) . ' ' . htmlspecialchars($_SESSION['client']['nom']); ?></div>
          <a href="php/historique.php">Historique des commandes</a>
          <form action="php/deconnexion.php" method="POST">
              <input type="hidden" name="token" value="<?php echo getToken(); ?>">
              <input type="submit" value="Se déconnecter">
          </form>
      <?php } ?>
    </div>
  </div>

  <p class="slogan">L'excellence mobile à portée de main. Découvrez notre sélection premium.</p>
  
  <table>
  <tr>
    <th>Modèle</th>
    <?php echo $noms; ?>
  </tr>
  <tr>
    <th>Aperçu</th>
    <?php echo $images; ?>
  </tr>
  <tr>
    <th>En stock</th>
    <?php echo $quantites; ?>
  </tr>
  <tr>
    <th>Prix</th>
    <?php echo $prix; ?>
  </tr>
  </table>

  <br>
  <?php if (isset($_SESSION['client'])) { ?>
      <a href="php/panier.php">Mon Panier</a>
  <?php } ?>
  <br>
  <a href="contact/contact.html">Contact</a>

<section id="fenetre-chat">
  <h2>Discussion</h2>

  <ul id="liste-messages"></ul>

  <input type="hidden" id="token-chat" value="<?php echo htmlspecialchars(getToken()); ?>">

  <label for="contenu-chat">Votre message</label>
  <input type="text" id="contenu-chat" maxlength="256">

  <button type="button" id="envoyer-chat">Envoyer</button>

  <p id="etat-chat" role="status"></p>
</section>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(document).ready(function() {
    function lireMessages() {
        $.ajax({
            url: "php/lire_messages.php",
            type: "GET",
            dataType: "json",
            success: function(reponse) {
                $("#liste-messages").empty();

                if (!reponse.succes) {
                    $("#etat-chat").text(reponse.message);
                    return;
                }

                for (let i = 0; i < reponse.messages.length; i++) {
                    let message = reponse.messages[i];
                    let ligne = message.auteur + " dit '" + message.contenu + "'";
                    $("#liste-messages").append($("<li>").text(ligne));
                }
            },
            error: function() {
                $("#etat-chat").text("Impossible de lire la discussion.");
            }
        });
    }

    $("#envoyer-chat").click(function() {
        $.ajax({
            url: "php/envoyer_message.php",
            type: "POST",
            data: {
                contenu: $("#contenu-chat").val(),
                token: $("#token-chat").val()
            },
            dataType: "json",
            success: function(reponse) {
                $("#etat-chat").text(reponse.message);

                if (reponse.succes) {
                    $("#contenu-chat").val("");
                }
            },
            error: function() {
                $("#etat-chat").text("Impossible d'envoyer le message.");
            }
        });
    });

    lireMessages();
    setInterval(lireMessages, 3000);
});
</script>
</body>
</html>