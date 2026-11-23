<?php
session_start();
if (!isset($_SESSION['client'])) {
    header("Location: ../index.php");
    exit();
}

require_once 'checkToken.php';
if (!isset($_POST["token"]) || isTokenNotValid($_POST["token"])) {
    header("Location: panier.php");
    exit();
}

if (!isset($_POST['id_art'])) {
    header("Location: panier.php");
    exit();
}

$id = $_POST['id_art'];
require_once 'bd.php';
$bdd = getBD();
$idClient = $_SESSION['client']['id_client'];

$rep = $bdd->prepare("SELECT COALESCE(SUM(quantite), 0) AS total FROM panier_tampon WHERE id_art = ? AND id_client = ?");
$rep->execute([$id, $idClient]);
$reservation = $rep->fetch(PDO::FETCH_ASSOC);
$rep->closeCursor();

$quantite = $reservation['total'];
if ($quantite > 0) {
    $majStock = $bdd->prepare("UPDATE articles SET quantite = quantite + ? WHERE id_art = ?");
    $majStock->execute([$quantite, $id]);
}

$supprime = $bdd->prepare("DELETE FROM panier_tampon WHERE id_art = ? AND id_client = ?");
$supprime->execute([$id, $idClient]);

if (isset($_SESSION["panier"])) {
    foreach($_SESSION["panier"] as $i => $article){
        if($article["id_art"] == $id){
            unset($_SESSION["panier"][$i]);
            break;
        }
    }
    $_SESSION["panier"] = array_values($_SESSION["panier"]);
}

header("Location: panier.php");
exit();
?>