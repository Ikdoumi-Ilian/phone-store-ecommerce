<?php
header('Content-Type: application/json; charset=utf-8');

if (!isset($_POST['mail']) || !is_string($_POST['mail']) || trim($_POST['mail']) === '') {
    http_response_code(400);
    echo json_encode(['existe' => false, 'message' => 'Veuillez saisir une adresse email.']);
    exit();
}

$mail = trim($_POST['mail']);
if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['existe' => false, 'message' => "L'adresse email est invalide."]);
    exit();
}

require_once 'bd.php';
try {
    $bdd = getBD();
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $req = $bdd->prepare('SELECT COUNT(*) FROM clients WHERE mail = ?');
    $req->execute([$mail]);
    $existe = $req->fetchColumn() > 0;
    $req->closeCursor();
    echo json_encode([
        'existe' => $existe,
        'message' => $existe
            ? 'Cette adresse email existe déjà.'
            : 'Cette adresse email est disponible.'
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['existe' => false, 'message' => 'Erreur lors de la vérification.']);
}
?>