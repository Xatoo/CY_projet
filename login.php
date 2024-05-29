<?php
// Démarrer la session
session_start();

$filename = 'Données/utilisateur.csv';
$separateur = ',';

// Vérifier si les informations de connexion sont soumises
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $username = $_POST["username"];
    $password = $_POST["password"];

    if (check_credentials($filename, $username, $password, $separateur)) {
        header("Location: index.html");
        exit();
    }
    else {
        header("Location: login.html");
        exit();
    }
}

function check_credentials($filename, $username, $password, $separateur) {
    $file = fopen($filename, "r");
    if ($file) {
        while ($line = fgetcsv($file, 1024, $separateur)) {
            $stored_login = $line[1];
            $stored_password = $line[2];
            if ($stored_login === $username && $stored_password === $password) {
                $_SESSION["loggedin"] = true;
                $_SESSION["username"] = $username;
                $_SESSION["id"] = $line[0]; 
                fclose($file);
                return true;
            }
        }
        fclose($file);
    }
    return false;
}
?>
