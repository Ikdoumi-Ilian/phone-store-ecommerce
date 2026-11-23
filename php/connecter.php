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

require_once 'checkToken.php';
if (!isset($_POST['token']) || !is_string($_POST['token']) || isTokenNotValid($_POST['token'])) {
    reponse(false, 'Formulaire expiré. Rechargez la page avant de recommencer.');
}
if (isset($_SESSION['client'])) {
    reponse(true, 'Vous êtes déjà connecté.');
}
if (!isset($_POST['mail'], $_POST['mdp']) || !is_string($_POST['mail']) || !is_string($_POST['mdp'])) {
    reponse(false, "L'adresse email et le mot de passe sont obligatoires.");
}
$mail = trim($_POST['mail']);
$mdp = $_POST['mdp'];
if ($mail === '' || $mdp === '') {
    reponse(false, "L'adresse email et le mot de passe sont obligatoires.");
}

require_once 'bd.php';
try {
    $bdd = getBD();
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $req = $bdd->prepare('SELECT * FROM clients WHERE mail = ?');
    $req->execute([$mail]);
    $client = $req->fetch(PDO::FETCH_ASSOC);
    $req->closeCursor();
    if ($client === false || !password_verify($mdp, $client['mdp'])) {
        reponse(false, 'Adresse email ou mot de passe incorrect.');
    }
    session_regenerate_id(true);
    $_SESSION['client'] = array(
        'id_client' => $client['id_client'],
        'prenom' => $client['prenom'],
        'nom' => $client['nom']
    );
    reponse(true, 'Connexion réussie !');
} catch (PDOException $e) {
    reponse(false, 'Erreur lors de la connexion à la base de données.');
}
?>