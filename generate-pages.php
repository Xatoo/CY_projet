<?php
// Chemin du fichier CSV
$chemin_fichier_csv = 'Données/conseil.csv';
$chemin_fichier_utilisateurs = 'Données/utilisateur.csv';

// Lire le fichier CSV et retourner un tableau associatif
function readCsv($file) {
    $rows = [];
    if (($handle = fopen($file, 'r')) !== FALSE) {
        $headers = fgetcsv($handle, 1000, ',');
        while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
            if (count($headers) !== count($data)) {
                // Log or handle the error, then continue to the next row
                continue;
            }
            $rows[] = array_combine($headers, $data);
        }
        fclose($handle);
    }
    return $rows;
}

// Charger le fichier CSV des utilisateurs
$utilisateurs = array_map('str_getcsv', file($chemin_fichier_utilisateurs));

// Initialiser la variable pour enregistrer le chemin du dernier fichier HTML créé
$lastHtmlFile = '';

// Fonction pour obtenir le prénom et le nom à partir de l'ID
function getUserName($id, $utilisateurs) {
    foreach ($utilisateurs as $utilisateur) {
        if ($utilisateur[0] == $id) {
            return $utilisateur[3] . ' ' . $utilisateur[4]; // Concaténer prénom et nom
        }
    }
    return ''; // Retourner une chaîne vide si l'ID n'est pas trouvé
}

// Fonction pour générer le contenu HTML pour chaque ligne
function generateHtml($row, $utilisateurs) {
    $id = htmlspecialchars($row['Id']);
    $title = htmlspecialchars($row['Titre']);
    $tease = htmlspecialchars($row['Texte_tease']);
    $date = (new DateTime($row['Date']))->format('d M Y');
    $userId = htmlspecialchars($row['Id-Auteur']);
    $user = getUserName($userId, $utilisateurs);
    $subject = htmlspecialchars($row['Matière']);
    $class = htmlspecialchars($row['Classe_recommandée']);
    $rating = htmlspecialchars($row['Notes']);
    $advice = nl2br(htmlspecialchars($row['Conseil']));
    $filename = "$id-$title.html";

    // Compteur pour les points
    $pointCount = 0;
    $adviceWithLineBreaks = '';

    // Parcourir chaque caractère du conseil
    for ($i = 0; $i < strlen($advice); $i++) {
        // Vérifier si le caractère est un point
        if ($advice[$i] === '.') {
            $pointCount++;
            // Si le compteur atteint trois, ajouter une balise <br> après le point
            if ($pointCount === 3) {
                $adviceWithLineBreaks .= '.';
                $adviceWithLineBreaks .= "<br>";
                $pointCount = 0; // Réinitialiser le compteur
                continue; // Passer au caractère suivant sans ajouter le point actuel
            }
        }
        $adviceWithLineBreaks .= $advice[$i];
    }
    
    return <<<HTML
    <!DOCTYPE html>
    <html lang="fr">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Conseillos Brothers - $title</title>
        <link rel="stylesheet" href="../../style.css">
    </head>
    <body data-conseil-id="$id">
    <main>
        <iframe src="../../nav.php" width="100%" height="127px" frameborder="0"></iframe>
        <br><br>
        <span><a href="page/1.php" class="categorie-link-main" style="font-size: 22px;">$subject</a></span>
        <span style="color: white; font-size: 22px">-</span>
        <span><a href="$filename" class="categorie-link-main" style="margin-left: 0%;font-size: 22px;">$title</a></span>
        <br><br><br>
        <div class="supp-text-main">Classe recommandée : $class</div>
        <br>
        <div class="supp-text-main">
            De <a href="../../account.html?id=$userId" class="profile-name">$user</a> le $date
        </div>
        <br><br><br>
        <div class="text-main">$adviceWithLineBreaks</div>
        <br><br>
        <div class="supp-text-main" style="text-align: center;">Votre avis compte</div>
        <div class="advice-rating-container">
            <div class="advice-rating">
                <span data-value="5">&#9733;</span>
                <span data-value="4">&#9733;</span>
                <span data-value="3">&#9733;</span>
                <span data-value="2">&#9733;</span>
                <span data-value="1">&#9733;</span>
            </div>
        </div>
        <div class="text-with-image" style="justify-content: left; padding-left: 25%;">
            <img src="../../../Images/bulle.png" height="30" style="margin-top: 14px; margin-right: 4px;">
            <h3>CONSEIL DISCUSSION</h3>
        </div>
        </br>
        <div class="supp-text-main">Laisser un commentaire</div>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            var stars = document.querySelectorAll('.advice-rating span');

            stars.forEach(function(star) {
                star.addEventListener('click', function() {
                    var rating = this.getAttribute('data-value')
                    var conseilId = document.body.getAttribute('data-conseil-id');

                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', '../../gestion-note.php', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === XMLHttpRequest.DONE) {
                            console.log(xhr.responseText);
                        }
                    };
                    xhr.send('&conseilId=' + conseilId + '&rating=' + rating);
                });
            });
        });
        </script>
    </main>
    </body>
    <footer>
        <iframe src="../../section.html" frameborder="0" width="100%" height="100%"></iframe>
        <iframe src="../../footer.html" width="100%" height="100%" frameborder="0"></iframe>
    </footer>
    </html>
HTML;
}

// Fonction pour vérifier l'existence des fichiers dans les dossiers de matières
function checkExistence($row, $utilisateurs) {
    $lastHtmlFile = '';
    $id = htmlspecialchars($row['Id']);
    $title = htmlspecialchars($row['Titre']);
    $subject = htmlspecialchars($row['Matière']);
    $filename = "$id-$title.html";
    $directory = "Conseil/$subject";

    if (!is_dir($directory)) {
        mkdir($directory, 0755, true);
    }

    if (!file_exists("$directory/$filename")) {
        $htmlContent = generatehtml($row, $utilisateurs);
        file_put_contents("$directory/$filename", $htmlContent);
        $lastHtmlFile = "$directory/$filename"; // Enregistrer le chemin du dernier fichier HTML créé
    }
    return $lastHtmlFile; // Retourner le chemin du dernier fichier HTML créé
}

// Lire les données du CSV
$rows = readCsv($chemin_fichier_csv);

// Vérifier l'existence des fichiers dans les dossiers de matières
foreach ($rows as $row) {
    $lastHtmlFile = checkExistence($row, $utilisateurs);
}

// Rediriger vers le dernier fichier HTML créé
if (!empty($lastHtmlFile)) {
    header("Location: $lastHtmlFile");
    exit;
}