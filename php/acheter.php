<?php
session_start();

if (!isset($_SESSION['client'])) {
    header("Location: ../index.php");
    exit();
}

require_once 'checkToken.php';
if (!isset($_POST["token"]) || isTokenNotValid($_POST["token"])) {
    header("Location: ../index.php");
    exit();
}

$panier = isset($_SESSION['panier']) ? $_SESSION['panier'] : [];

if (count($panier) == 0) {
    header("Location: panier.php");
    exit();
}

require_once 'bd.php';
$idClient = $_SESSION['client']['id_client'];

try {
    $bdd = getBD();
    
    nettoyerPanierTampon($bdd, $idClient);
    $panier = isset($_SESSION['panier']) ? $_SESSION['panier'] : [];
    
    if (count($panier) == 0) {
        header("Location: panier.php");
        exit();
    }

    $ins = $bdd->prepare("INSERT INTO commandes (id_art, id_client, quantite, envoi, prix) VALUES (?, ?, ?, 0, ?)");
    $reqPrix = $bdd->prepare("SELECT prix FROM articles WHERE id_art = ?");
    
    $delTampon = $bdd->prepare("DELETE FROM panier_tampon WHERE id_client = ?");
    
    foreach ($panier as $article) {
        $reqPrix->execute([$article["id_art"]]);
        $produit = $reqPrix->fetch(PDO::FETCH_ASSOC);
        $prix = $produit ? $produit['prix'] : 0;
        
        $ins->execute([$article["id_art"], $idClient, $article["qte"], $prix]);
    }
    
    $delTampon->execute([$idClient]);
    unset($_SESSION['panier']);
    
} catch (Exception $e) {
    die("Une erreur est survenue lors de l'enregistrement de votre commande.");
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Confirmation de commande - Phone Store</title>
  <link rel="stylesheet" href="../styles/style.css" type="text/css">
</head>
<body>

  <h1>Confirmation</h1>
  <p>Votre commande a bien été enregistrée.</p>
  
  <br>
  <a href="../index.php">Retour à la boutique</a>

</body>
</html>