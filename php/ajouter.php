<?php
session_start();

if (!isset($_SESSION["client"])) {
    header("Location: ../index.php");
    exit();
}

require_once 'checkToken.php';
if (!isset($_POST["token"]) || isTokenNotValid($_POST["token"])) {
    header("Location: ../index.php");
    exit();
}

if (!isset($_POST["id_art"]) || !isset($_POST["qte"])) {
    header("Location: ../index.php");
    exit();
}

$id = $_POST["id_art"];
$quantite = filter_var($_POST["qte"], FILTER_VALIDATE_INT);

if ($quantite === false || $quantite < 1) {
    header("Location: ../index.php");
    exit();
}

require_once 'bd.php';
$bdd = getBD();

nettoyerPanierTampon($bdd, $_SESSION["client"]["id_client"]);

$rep = $bdd->prepare("SELECT * FROM articles WHERE id_art = ?");
$rep->execute([$id]);
$produit = $rep->fetch(PDO::FETCH_ASSOC);

if (!$produit) {
    header("Location: ../index.php");
    exit();
}

$quantiteMax = $produit["quantite"];
if ($quantiteMax < 1) {
    header("Location: ../index.php");
    exit();
}

$quantiteFinale = min($quantite, $quantiteMax);

$reserve = $bdd->prepare("UPDATE articles SET quantite = quantite - ? WHERE id_art = ? AND quantite >= ?");
$reserve->execute([$quantiteFinale, $id, $quantiteFinale]);

if ($reserve->rowCount() === 0) {
    header("Location: ../index.php");
    exit();
}

$insTampon = $bdd->prepare("INSERT INTO panier_tampon (id_art, id_client, quantite) VALUES (?, ?, ?)");
$insTampon->execute([$id, $_SESSION["client"]["id_client"], $quantiteFinale]);

if (!isset($_SESSION["panier"])) {
    $_SESSION["panier"] = [
        ["id_art" => $id, "qte" => $quantiteFinale]
    ];
} else {
    for ($i = 0; $i < count($_SESSION["panier"]); $i++) {
        if ($_SESSION["panier"][$i]["id_art"] == $id) {
            $_SESSION["panier"][$i]["qte"] += $quantiteFinale;
            header("Location: ../index.php");
            exit();
        }
    }
    $_SESSION["panier"][] = ["id_art" => $id, "qte" => $quantiteFinale];
}

header("Location: ../index.php");
exit();
?>