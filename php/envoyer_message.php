<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

require_once 'checkToken.php';
require_once 'bd.php';
require_once 'filtrer_message.php';

function reponseChat($succes, $message) {
    echo json_encode([
        'succes' => $succes,
        'message' => $message
    ]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    reponseChat(false, 'Méthode non autorisée.');
}

if (!isset($_POST['token']) || !is_string($_POST['token'])
    || isTokenNotValid($_POST['token'])) {
    reponseChat(false, 'Formulaire expiré. Rechargez la page.');
}

if (!isset($_POST['contenu']) || !is_string($_POST['contenu'])) {
    reponseChat(false, 'Saisissez un message.');
}

$contenu = trim($_POST['contenu']);

if (preg_match('/\A.{1,256}\z/us', $contenu) !== 1) {
    reponseChat(false, 'Le message doit contenir entre 1 et 256 caractères.');
}

try {
    $score = calculerScoreMessage($contenu);
} catch (Exception $e) {
    reponseChat(false, 'Vérification du message indisponible. Réessayez plus tard.');
}

if ($score < 0) {
    reponseChat(false, 'Ce message a été refusé par le filtre automatique.');
}

$idClient = null;

if (isset($_SESSION['client']['id_client'])) {
    $idClient = $_SESSION['client']['id_client'];
}

try {
    $bdd = getBD();
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $bdd->exec('SET NAMES utf8mb4');

    $requete = $bdd->prepare(
        'INSERT INTO messages (id_client, contenu) VALUES (?, ?)'
    );
    $requete->execute([$idClient, $contenu]);
} catch (PDOException $e) {
    reponseChat(false, "Impossible d'enregistrer le message.");
}

reponseChat(true, 'Message envoyé.');