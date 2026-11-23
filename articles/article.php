<?php
session_start();
include '../php/bd.php';
include '../php/checkToken.php';
$bdd = getBD();
if (isset($_SESSION['client'])) {
    nettoyerPanierTampon($bdd, $_SESSION['client']['id_client']);
}
if (!isset($_GET['id_art'])) {
    header("Location: ../index.php");
    exit();
}
$id = $_GET['id_art'];

$rep = $bdd->prepare('SELECT * FROM articles WHERE id_art = ?');
$rep->execute(array($id));

$article = $rep->fetch(PDO::FETCH_ASSOC);
$rep->closeCursor();
if ($article === false) {
    header("Location: ../index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="../styles/style.css" type="text/css">
  <title><?php echo htmlspecialchars($article['nom']); ?> - Phone Store</title>
</head>
<body>

  <h1><?php echo htmlspecialchars($article['nom']); ?></h1>

  <div style="text-align: center; margin: 40px 0;">
    <img class="image-article" src="../<?php echo htmlspecialchars($article['url_photo']); ?>" alt="<?php echo htmlspecialchars($article['nom']); ?>">
  </div>

  <h2>Description</h2>
  <p><?php echo htmlspecialchars($article['description']); ?></p>
  
  <h2>Détails</h2>
  <ul>
    <li><strong>Prix :</strong> <?php echo $article['prix']; ?> €</li>
    <li><strong>En stock :</strong> <?php echo $article['quantite']; ?> exemplaires</li>
  </ul>

  <?php if (isset($_SESSION['client'])) { ?>
      <form action="../php/ajouter.php" method="POST">
          <input type="hidden" name="id_art" value="<?php echo $article['id_art']; ?>">
          <input type="hidden" name="token" value="<?php echo getToken(); ?>">
          <p>Quantité : <input type="number" name="qte" value="1" min="1"></p>
          <input type="submit" value="Ajoutez à votre panier">
      </form>
  <?php } ?>

  <br>
  <a href="../index.php">Retour à la boutique</a> 

</body>
</html>