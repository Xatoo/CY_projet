<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.html");
    exit();
}

// Récupérer les données du formulaire
$filename = 'Données/utilisateur.csv';
$separateur = ',';

// Vérifier si les informations de changement de mot de passe sont soumises
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer l'username de la session
    $session_username = $_SESSION["username"];

    // Récupérer l'username envoyé via la méthode POST
    $post_username = $_POST["username"];

    // Vérifier si les usernames correspondent
    if ($session_username !== $post_username) {
        echo "Erreur : L'username de session ne correspond pas à l'username envoyé.";
        echo '<meta http-equiv="refresh" content="2; url=change-password.html">';
        exit(); // Arrêter le script pour éviter d'exécuter la suite du code
    }

    // Récupérer le nouveau mot de passe
    $new_password = $_POST["new-password"];

    if (change_password($filename, $session_username, $new_password, $separateur)) {
        echo "Mot de passe changé avec succès.";
        echo '<meta http-equiv="refresh" content="1; url=index.html">';
    } else {
        echo "Erreur lors du changement de mot de passe.";
        echo '<meta http-equiv="refresh" content="0; url=change-password.html">';
        exit(); // Rediriger immédiatement après l'affichage du message
    }
}

function change_password($filename, $username, $new_password, $separateur) {
    $rows = [];
    $file = fopen($filename, "r");
    if ($file) {
        while ($line = fgetcsv($file, 1024, $separateur)) {
            if ($line[1] === $username) {
                $line[2] = $new_password; 
            }
            $rows[] = $line;
        }
        fclose($file);
    }

    $file = fopen($filename, "w");
    if ($file) {
        foreach ($rows as $row) {
            fputcsv($file, $row, $separateur);
        }
        fclose($file);
        return true;
    }
    return false;
}