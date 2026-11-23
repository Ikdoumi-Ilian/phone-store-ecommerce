<?php
session_start();
if (!isset($_SESSION['client'])) {
    header("Location: ../index.php");
    exit();
}

require_once 'bd.php';

$bdd = getBD();
$rep = $bdd->prepare("SELECT c.id_commande, c.id_art, a.nom, c.prix, c.quantite, c.envoi 
                      FROM commandes c 
                      LEFT JOIN articles a ON a.id_art = c.id_art 
                      WHERE c.id_client = ? 
                      ORDER BY c.id_commande");
$rep->execute([$_SESSION['client']['id_client']]);
$commandes = $rep->fetchAll(PDO::FETCH_ASSOC);
$rep->closeCursor();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Historique des commandes</title>
  <link rel="stylesheet" href="../styles/style.css" type="text/css">
</head>
<body>

  <h1>Historique des commandes</h1>
  <p><a href="../index.php">Retour à la boutique</a></p>

  <?php if (count($commandes) == 0) { ?>
      <p>Vous n'avez passé aucune commande.</p>
  <?php } else { ?>
      <table class="tableau-commande">
          <tr>
              <th>Commande</th>
              <th>Id article</th>
              <th>Nom</th>
              <th>Prix payé</th>
              <th>Quantité</th>
              <th>État</th>
          </tr>
          <?php foreach ($commandes as $commande) { ?>
              <tr>
                  <td><?php echo $commande["id_commande"]; ?></td>
                  <td><?php echo $commande["id_art"]; ?></td>
                  <td><?php echo isset($commande["nom"]) ? htmlspecialchars($commande["nom"]) : "Produit supprimé"; ?></td>
                  <!-- On affiche le prix stocké dans la commande -->
                  <td><?php echo isset($commande["prix"]) ? number_format($commande["prix"], 2, ',', ' ') . ' €' : "-"; ?></td>
                  <td><?php echo $commande["quantite"]; ?></td>
                  <td><?php echo $commande["envoi"] ? "Envoyée" : "Non envoyée"; ?></td>
              </tr>
          <?php } ?>
      </table>
  <?php } ?>

</body>
</html>