<?php
session_start();

if (!isset($_SESSION['id'])) {
    // L'utilisateur n'est pas connecté, vous pouvez gérer cela selon vos besoins
    echo 'Vous devez être connecté pour noter.';
    echo '<meta http-equiv="refresh" content="1; url=index.html">';
    exit;
}

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $userId = $_SESSION["id"];

    // Récupérer les données du formulaire
    $titre = trim(preg_replace('/\s+/', ' ', htmlspecialchars($_POST["titre"])));
    $texteTease = trim(preg_replace('/\s+/', ' ', htmlspecialchars($_POST["texteTease"])));
    $conseil = trim(preg_replace('/\s+/', ' ', htmlspecialchars($_POST['conseil'])));
    $matiere = $_POST["matiere"];
    $classeRecommandee = $_POST["classeRecommandee"];
    $date = date("Y-m-d"); 
    // Note par défaut à 0
    $notes = 0;

    $conseilFile = 'Données/conseil.csv';

    // Ouvrir le fichier CSV en mode ajout
    $file = fopen($conseilFile, 'a');

    // Vérifier si l'ouverture du fichier a réussi
    if ($file) {

        // Créer une nouvelle ligne CSV avec les données du formulaire
        $newData = array(
            count(file($conseilFile)), // Id incrémenté
            $titre,
            $texteTease,
            $date,
            $userId,
            $matiere,
            $classeRecommandee,
            $notes,
            $conseil
        );

        // Écrire la nouvelle ligne dans le fichier CSV
        fputcsv($file, $newData);

        // Fermer le fichier
        fclose($file);
    }
}

include 'generate_pages.php';
?>
