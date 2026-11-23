<?php
session_start();
if (!isset($_SESSION['client'])) {
    header("Location: ../index.php");
    exit();
}

require_once 'checkToken.php';
require_once 'bd.php';
$bdd = getBD();
nettoyerPanierTampon($bdd, $_SESSION['client']['id_client']);
$panier = isset($_SESSION['panier']) ? $_SESSION['panier'] : [];
$articles = [];
$total = 0;

if (count($panier) == 0) {
    header("Location: panier.php");
    exit();
}

$ids = [];
foreach ($panier as $article) {
    $ids[] = $article['id_art'];
}

$places = implode(',', array_fill(0, count($ids), '?'));
$rep = $bdd->prepare("SELECT * FROM articles WHERE id_art IN ($places)");
$rep->execute($ids);
while ($produit = $rep->fetch(PDO::FETCH_ASSOC)) {
    $articles[$produit['id_art']] = $produit;
}
$rep->closeCursor();

$adresse = isset($_SESSION['client']['adresse']) ? $_SESSION['client']['adresse'] : '';
if ($adresse == '') {
    try {
        $rep = $bdd->prepare("SELECT adresse FROM clients WHERE id_client = ?");
        $rep->execute([$_SESSION['client']['id_client']]);
        $ligne = $rep->fetch(PDO::FETCH_ASSOC);
        if ($ligne && isset($ligne['adresse'])) {
            $adresse = $ligne['adresse'];
        }
        $rep->closeCursor();
    } catch (PDOException $e) {
        $adresse = '';
    }
}
if ($adresse == '') {
    $adresse = "Adresse non renseignée";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Récapitulatif de votre commande</title>
  <link rel="stylesheet" href="../styles/style.css" type="text/css">
</head>
<body>

  <h1>Récapitulatif de votre commande</h1>
  <p><a href="../index.php">Retour à la boutique</a></p>

  <table class="tableau-commande">
      <tr>
          <th>Produit</th>
          <th>Prix unitaire</th>
          <th>Quantité</th>
          <th>Sous-total</th>
      </tr>
      <?php 
      foreach ($panier as $article) {
          $produit = isset($articles[$article['id_art']]) ? $articles[$article['id_art']] : null;
          if (!$produit) {
              continue;
          }
          $sousTotal = $produit['prix'] * $article['qte'];
          $total += $sousTotal;
      ?>
          <tr>
              <td><?php echo htmlspecialchars($produit['nom']); ?></td>
              <td><?php echo number_format($produit['prix'], 2, ',', ' '); ?> €</td>
              <td><?php echo $article['qte']; ?></td>
              <td><?php echo number_format($sousTotal, 2, ',', ' '); ?> €</td>
          </tr>
      <?php } ?>
  </table>

  <p><strong>Montant de votre commande :</strong> <?php echo number_format($total, 2, ',', ' '); ?> €</p>

  <p>La commande sera expédiée à l'adresse suivante :</p>
  <p>
      <?php echo htmlspecialchars($_SESSION['client']['nom']); ?> <?php echo htmlspecialchars($_SESSION['client']['prenom']); ?><br>
      <?php echo nl2br(htmlspecialchars($adresse)); ?>
  </p>
  <form action="acheter.php" method="POST">
      <input type="hidden" name="token" value="<?php echo getToken(); ?>" />
      <input type="submit" value="Valider">
  </form>

</body>
</html>