<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

require_once 'bd.php';

try {
    $bdd = getBD();
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $bdd->exec('SET NAMES utf8mb4');

    $bdd->exec(
        'DELETE FROM messages
         WHERE date_envoi < DATE_SUB(NOW(), INTERVAL 10 MINUTE)'
    );

    $requete = $bdd->query(
        "SELECT COALESCE(clients.prenom, 'Invité') AS auteur,
                messages.contenu
         FROM messages
         LEFT JOIN clients ON messages.id_client = clients.id_client
         WHERE messages.date_envoi >= DATE_SUB(NOW(), INTERVAL 10 MINUTE)
         ORDER BY messages.id_message ASC"
    );

    $messages = $requete->fetchAll(PDO::FETCH_ASSOC);
    $requete->closeCursor();

    echo json_encode([
        'succes' => true,
        'messages' => $messages
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'succes' => false,
        'message' => 'Impossible de lire la discussion.'
    ]);
}