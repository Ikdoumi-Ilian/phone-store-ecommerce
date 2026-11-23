<?php

function calculerScoreMessage($texte) {
    $cheminPoids = __DIR__ . '/../modele/poids.json';
    $cheminParametres = __DIR__ . '/../modele/parametres_modele.json';

    if (!is_readable($cheminPoids) || !is_readable($cheminParametres)) {
        throw new Exception('Fichiers du modèle indisponibles.');
    }

    $contenuPoids = @file_get_contents($cheminPoids);
    $contenuParametres = @file_get_contents($cheminParametres);

    if ($contenuPoids === false || $contenuParametres === false) {
        throw new Exception('Lecture du modèle impossible.');
    }

    $poids = json_decode($contenuPoids, true);
    $parametres = json_decode($contenuParametres, true);

    if (!is_array($poids) || !is_array($parametres)) {
        throw new Exception('JSON du modèle invalide.');
    }

    if (!isset(
        $parametres['biais'],
        $parametres['occurrences'],
        $parametres['stopwords']
    )) {
        throw new Exception('Paramètres du modèle incomplets.');
    }

    if (!is_array($parametres['occurrences'])
        || !is_array($parametres['stopwords'])
        || !is_numeric($parametres['biais'])) {
        throw new Exception('Paramètres du modèle invalides.');
    }

    if (!function_exists('mb_strtolower')) {
        throw new Exception('Extension mbstring indisponible.');
    }

    $symboles = "!.,;:.&\"'()[]{@}<>?@#$%^*+-=~`|/\\_1234567890\n\t";
    $texte = str_replace(str_split($symboles), ' ', $texte);

    $accents = [
        'é' => 'e', 'è' => 'e', 'à' => 'a',
        'î' => 'i', 'ï' => 'i', 'ê' => 'e',
        'â' => 'a', 'ë' => 'e',
        'É' => 'e', 'È' => 'e', 'À' => 'a',
        'Î' => 'i', 'Ï' => 'i', 'Ê' => 'e',
        'Â' => 'a', 'Ë' => 'e',
        'ù' => 'u', 'û' => 'u', 'ü' => 'u',
        'Ù' => 'u', 'Û' => 'u', 'Ü' => 'u',
        'ô' => 'o', 'ö' => 'o',
        'Ô' => 'o', 'Ö' => 'o'
    ];

    $texte = strtr($texte, $accents);
    $texte = mb_strtolower($texte, 'UTF-8');

    $mots = preg_split(
        '/\s+/u',
        $texte,
        -1,
        PREG_SPLIT_NO_EMPTY
    );

    $motsConserves = [];

    foreach ($mots as $mot) {
        if (!in_array($mot, $parametres['stopwords'], true)) {
            $motsConserves[] = $mot;
        }
    }

    if (count($motsConserves) === 0) {
        return 0;
    }

    $nombreMots = count($motsConserves);
    $score = (float) $parametres['biais'];

    foreach ($motsConserves as $mot) {
        if (isset($poids[$mot], $parametres['occurrences'][$mot])) {
            $contribution = 1 / $nombreMots;
            $contribution *= 1 / $parametres['occurrences'][$mot];
            $contribution *= $poids[$mot];

            $score += $contribution;
        }
    }

    return $score;
}