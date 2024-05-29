<?php
session_start();
if (!isset($_SESSION['id'])) {
    // L'utilisateur n'est pas connecté, vous pouvez gérer cela selon vos besoins
    echo 'Vous devez être connecté pour noter.';
    exit;
}

$userId = $_SESSION['id'];
$conseilId = $_POST['conseilId'];
$rating = $_POST['rating'];

$noteFile = 'Données/note.csv';
$conseilFile = 'Données/conseil.csv';
$found = false;
$noteLines = file($noteFile);
$newNoteLines = '';

// Mettre à jour la note dans le fichier note.csv
foreach ($noteLines as $line) {
    $data = str_getcsv($line);
    if ($data[0] == $userId && $data[1] == $conseilId) {
        // Mettre à jour la note existante
        $line = "$userId,$conseilId,$rating\n";
        $newNoteLines .= $line;
        $found = true;
    } else {
        // Conserver les autres lignes telles quelles
        $newNoteLines .= $line;
    }
}

// Si la note n'a pas été trouvée, ajouter une nouvelle entrée dans le fichier CSV
if (!$found) {
    $data = "$userId,$conseilId,$rating\n";
    $newNoteLines .= $data;
}

file_put_contents($noteFile, $newNoteLines);

// Recharger les lignes du fichier note.csv après mise à jour
$noteLines = file($noteFile);

// Calculer la nouvelle note moyenne dans le fichier note.csv
$totalRating = 0;
$totalUsers = 0;

foreach ($noteLines as $line) {
    $data = str_getcsv($line);
    if ($data[1] == $conseilId) {
        $totalRating += floatval($data[2]);
        $totalUsers++;
    }
}

$newAverageRating = $totalRating / $totalUsers;

// Mettre à jour la note moyenne dans le fichier conseil.csv
$conseilLines = file($conseilFile);
$newConseilLines = '';

foreach ($conseilLines as $index => $line) {
    if ($index == 0) {
        // Ignorer la première ligne
        $newConseilLines .= $line;
        continue;
    }
    $data = str_getcsv($line);
    if ($data[0] == $conseilId) {
        // Mettre à jour la note moyenne
        $data[7] = $newAverageRating;
        // Réécrire la ligne formatée correctement
        $data[2] = '"' . $data[2] . '"';
        $data[8] = '"' . $data[8] . '"';
        $newLine = implode(',', $data) . "\n";
        $newConseilLines .= $newLine;
    } else {
        // Conserver les autres lignes telles quelles
        $newConseilLines .= $line;
    }
}

file_put_contents($conseilFile, $newConseilLines);

echo 'Note enregistrée avec succès. La note moyenne du conseil a été mise à jour.';
?>