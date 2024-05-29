<?php
session_start(); // Démarre la session
session_unset(); // Supprime toutes les variables de session
session_destroy(); // Détruit la session

// Alimentation des variables
$filename = 'Données/utilisateur.csv';
$separateur = ',';

// Récupérer les données envoyé via la méthode POST
    $post_new_username = $_POST["new_username"];
    $post_new_pwd = $_POST["new_pwd"];
    $post_new_uname = $_POST["new_uname"];
    $post_new_u1name = $_POST["new_u1name"];
    $new_id = count(file($filename));

// Ajoute les données dans le fichier CSV
if (ajout_utilisateur($filename, $post_new_username, $post_new_pwd, $post_new_uname, $post_new_u1name, $separateur, $new_id)) {
        session_start();
        echo "Compte utilisateur créé avec succès.";
        $_SESSION["loggedin"] = true;
        $_SESSION["username"] = $post_new_username;
        $_SESSION["id"] = $new_id;
        echo '<meta http-equiv="refresh" content="2; url=index.html">';
    } else {
        echo "Erreur lors de la création du compte.";
        echo '<meta http-equiv="refresh" content="1; url=inscription.html">';
        exit(); // Rediriger immédiatement après l'affichage du message
    }

// Fonction pour ajouter les données dans le fichier CSV si le login n'existe pas déjà
function ajout_utilisateur($filename, $post_new_username, $post_new_pwd, $post_new_uname, $post_new_u1name, $separateur, $new_id) {
    $file = fopen($filename, "r");
    if ($file) {
        while ($line = fgetcsv($file, 1024, $separateur)) {
            if ($line[1] === $post_new_username) {
                echo "Le login utilisateur existe déjà - Choisir un autre login.";
                echo '<meta http-equiv="refresh" content="2; url=inscription.html">';
                exit(); // Rediriger immédiatement après l'affichage du message
            }
        }
        fclose($file);
    }

    // Données à ajouter
    $new_row = [
        'Colonne0' => $new_id,
        'Colonne1' => $post_new_username,
        'Colonne2' => $post_new_pwd,
        'Colonne3' => $post_new_uname,
        'Colonne4' => $post_new_u1name,
    ];

    // Ouvrir le fichier en mode ajout
    $file = fopen($filename, 'a');

    if ($file) {
        // Écrire la nouvelle ligne dans le fichier
        fputcsv($file, $new_row, $separateur);
        fclose($file);
        return true;
    }
    else {
    return false;
    }
    
}