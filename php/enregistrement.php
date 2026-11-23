<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

function reponse($succes, $message) {
    echo json_encode(['succes' => $succes, 'message' => $message]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    reponse(false, 'Méthode non autorisée.');
}
if (isset($_SESSION['client'])) {
    reponse(false, 'Vous êtes déjà connecté.');
}

require_once 'checkToken.php';
if (!isset($_POST['token']) || !is_string($_POST['token']) || isTokenNotValid($_POST['token'])) {
    reponse(false, 'Formulaire expiré. Rechargez la page avant de recommencer.');
}

foreach (['n', 'p', 'adr', 'num', 'mail', 'mdp1', 'mdp2'] as $champ) {
    if (!isset($_POST[$champ]) || !is_string($_POST[$champ])) {
        reponse(false, 'Tous les champs sont obligatoires et doivent contenir du texte.');
    }
}
$n = trim($_POST['n']);
$p = trim($_POST['p']);
$adr = trim($_POST['adr']);
$num = trim($_POST['num']);
$mail = trim($_POST['mail']);
$mdp1 = $_POST['mdp1'];
$mdp2 = $_POST['mdp2'];

if ($n === '' || $p === '' || $adr === '' || $num === '' || $mail === '' || $mdp1 === '' || $mdp2 === '') {
    reponse(false, 'Tous les champs sont obligatoires.');
}
if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
    reponse(false, "L'adresse email est invalide.");
}
if ($mdp1 !== $mdp2) {
    reponse(false, 'Les mots de passe ne correspondent pas.');
}
if (!preg_match('/^(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[^a-zA-Z0-9\s])\S+$/', $mdp1)) {
    reponse(false, 'Le mot de passe doit contenir une lettre, un chiffre et un caractère spécial, sans espace.');
}

require_once 'bd.php';
try {
    $bdd = getBD();
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $req = $bdd->prepare('SELECT COUNT(*) FROM clients WHERE mail = ?');
    $req->execute([$mail]);
    $existe = $req->fetchColumn() > 0;
    $req->closeCursor();
    if ($existe) {
        reponse(false, 'Cette adresse email existe déjà.');
    }

    $mdp = password_hash($mdp1, PASSWORD_DEFAULT);
    $req = $bdd->prepare('INSERT INTO clients (nom, prenom, adresse, numero, mail, mdp) VALUES (?, ?, ?, ?, ?, ?)');
    $req->execute([$n, $p, $adr, $num, $mail, $mdp]);
    $id = $bdd->lastInsertId();
    session_regenerate_id(true);
    $_SESSION['client'] = array(
        'id_client' => $id,
        'prenom' => $p,
        'nom' => $n
    );
    reponse(true, 'Votre compte a été créé avec succès !');
} catch (PDOException $e) {
    if (isset($e->errorInfo[1]) && $e->errorInfo[1] == 1062) {
        reponse(false, 'Cette adresse email existe déjà.');
    }
    reponse(false, "Erreur lors de l'enregistrement dans la base de données.");
} catch (Throwable $e) {
    reponse(false, "Erreur lors de l'enregistrement du compte.");
}
?>