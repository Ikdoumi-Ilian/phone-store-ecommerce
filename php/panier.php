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

if (count($panier) > 0) {
    $ids = [];
    foreach ($panier as $item) {
        $ids[] = $item['id_art'];
    }
    $places = implode(',', array_fill(0, count($ids), '?'));
    $rep = $bdd->prepare("SELECT * FROM articles WHERE id_art IN ($places)");
    $rep->execute($ids);
    while ($produit = $rep->fetch(PDO::FETCH_ASSOC)) {
        $articles[$produit['id_art']] = $produit;
    }
    $rep->closeCursor();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="../styles/style.css" type="text/css">
  <title>Mon Panier - Phone Store</title>
</head>
<body>

  <h1>Votre Panier</h1>
  <p><a href="../index.php">Retour à la boutique</a></p>

  <?php if (count($panier) == 0) { ?>
      <p>Votre panier est vide.</p>
  <?php } else { ?>
      <table class="tableau-commande">
          <tr>
              <th>Produit</th>
              <th>Prix unitaire</th>
              <th>Quantité</th>
              <th>Sous-total</th>
              <th>Action</th>
          </tr>
          <?php 
          foreach ($panier as $item) {
              $produit = isset($articles[$item['id_art']]) ? $articles[$item['id_art']] : null;
              if ($produit) {
                  $sousTotal = $produit['prix'] * $item['qte'];
                  $total += $sousTotal;
          ?>
              <tr>
                  <td><?php echo htmlspecialchars($produit['nom']); ?></td>
                  <td><?php echo $produit['prix']; ?> €</td>
                  <td><?php echo $item['qte']; ?></td>
                  <td><?php echo $sousTotal; ?> €</td>
                  <td>
                      <form action="retirer.php" method="POST">
                          <input type="hidden" name="id_art" value="<?php echo $item['id_art']; ?>">
                          <input type="hidden" name="token" value="<?php echo getToken(); ?>" />
                          <input type="submit" value="Retirer">
                      </form>
                  </td>
              </tr>
          <?php 
              }
          } 
          ?>
      </table>
      <p><strong>Montant total : <?php echo $total; ?> €</strong></p>

      <p><a href="commande.php">Passer la commande</a></p>
  <?php } ?>

</body>
</html>