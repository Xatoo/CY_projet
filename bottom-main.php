<?php

// Chemin du fichier CSV
$chemin_fichier_csv = 'Données/conseil.csv';
$chemin_fichier_utilisateurs = 'Données/utilisateur.csv';

// Charger le fichier CSV
$conseils = array_map('str_getcsv', file($chemin_fichier_csv));

// Charger le fichier CSV des utilisateurs
$utilisateurs = array_map('str_getcsv', file($chemin_fichier_utilisateurs));

// Fonction pour obtenir le prénom et le nom à partir de l'ID
function getUserName($id, $utilisateurs) {
    foreach ($utilisateurs as $utilisateur) {
        if ($utilisateur[0] == $id) {
            return $utilisateur[3] . ' ' . $utilisateur[4]; // Concaténer prénom et nom
        }
    }
    return ''; // Retourner une chaîne vide si l'ID n'est pas trouvé
}

function filterConseils($conseils, $matiere) {
    // Filtrer les conseils pour la matière spécifiée
    $conseils_filtres = array_filter($conseils, function($conseil) use ($matiere) {
        return $conseil[5] === $matiere;
    });

    // Trier les conseils filtrés par notes décroissantes
    usort($conseils_filtres, function($a, $b) {
        $noteA = (float) $a[7]; // Convertir la note en nombre flottant
        $noteB = (float) $b[7]; // Convertir la note en nombre flottant
        return $noteB - $noteA; // Comparer les notes de $b avec celles de $a pour un tri décroissant
    });

    // Prendre les 5 premiers conseils triés
    $conseils_filtres = array_slice($conseils_filtres, 0, 5);

    return $conseils_filtres;
}

function generateStars($note) {
    $html_stars = '';
    // Boucle pour générer les étoiles
    for ($i = 1; $i <= 5; $i++) {
        // Vérifier si l'étoile doit être remplie, demi-remplie ou vide
        if ($note >= $i) {
            // Étoile remplie
            $html_stars .= '<span class="star filled">&#9733;</span>';
        } else {
            // Étoile vide
            $html_stars .= '<span class="star">&#9733;</span>';
        }
    }
    return $html_stars;
}

function generateHTMLConseils($conseils, $matiere, $utilisateurs) {
    $html_conseils = '';

    $conseils_matiere = filterConseils($conseils, $matiere);

    foreach ($conseils_matiere as $conseil) {
        // Générer le nom de fichier en utilisant l'id et le titre
        $filename = 'Conseil/' . $conseil[5] . '/' . $conseil[0] . '-' . rawurlencode($conseil[1]) . '.html';

        // Obtenir le prénom et le nom de l'utilisateur à partir de l'ID
        $userName = getUserName($conseil[4], $utilisateurs);

        // Générer les étoiles en fonction de la note du conseil
        $html_stars = generateStars($conseil[7]);

        $html_conseils .= '
        <a target="_parent" class="card-link" href="../' . $filename . '">
            <div class="card-conseils">
                <div class="card-category">' . $conseil[5] . '</div>
                <div class="card-etude">' . $conseil[6] . '</div>
                <div class="card-title">' . $conseil[1] . '</div>
                <div class="card-tease-text">' . $conseil[2] . '</div>
                <div class="card-date">' . $conseil[3] . '</div>
                <div class="stars">
                    ' . $html_stars . '
                </div>
            </div>
        </a>';
    }

    return $html_conseils;
}

// Appeler la fonction pour générer le HTML des conseils pour la matière Mathématique
$html_conseils_mathematique = generateHTMLConseils($conseils, "Mathématique", $utilisateurs);

// Appeler la fonction pour générer le HTML des conseils pour la matière Physique
$html_conseils_physique = generateHTMLConseils($conseils, "Physique", $utilisateurs);

// Appeler la fonction pour générer le HTML des conseils pour la matière SVT
$html_conseils_svt = generateHTMLConseils($conseils, "SVT", $utilisateurs);

// Appeler la fonction pour générer le HTML des conseils pour la matière Anglais
$html_conseils_anglais = generateHTMLConseils($conseils, "Anglais", $utilisateurs);

// Appeler la fonction pour générer le HTML des conseils pour la matière français
$html_conseils_francais = generateHTMLConseils($conseils, "Français", $utilisateurs);

// Appeler la fonction pour générer le HTML des conseils pour la matière Philosophie
$html_conseils_philosophie = generateHTMLConseils($conseils, "Philosophie", $utilisateurs);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>

    body{
      background-color: #1a2226;  
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    .card-link {
      text-decoration: none;
      margin: 0.5%;
      margin-bottom : 4%;
    }

    .carousel {
      position: relative;
      width: 966px;
      margin: auto;
      overflow: hidden;
      border-radius: 10px;
    }

    .carousel-container {
      display: flex;
      transition: transform 0.5s ease;
      margin: 0%;
    }

    .card-conseils {
      width: 300px;
      height: 105px;
      background: #283135;
      padding: .4em;
      border-radius: 6px;
      position: relative;
      flex-shrink: 0;
    }

    .card-conseils:hover {
    transform: scale(1.02);
    }

    .card-category,
    .card-etude,
    .card-title,
    .card-tease-text,
    .card-date,
    .stars {
      color: white;
    }

    .card-category {
      position: absolute;
      top: 5px;
      left: 10px;
      font-size: 14px;
    }

    .card-etude {
      position: absolute;
      top: 5px;
      right: 10px;
      font-size: 14px;
    }

    .card-title {
      font-weight: 600;
      text-align: center;
      padding-top: 20px;
    }

    .card-tease-text {
      text-align: center;
      width: 240px;
      margin: auto;
      padding-top: 8px;
      font-size: 0.9em;
    }

    .card-date {
      position: absolute;
      bottom: 5px;
      left: 10px;
      font-size: 14px;
    }

    .stars {
      position: absolute;
      bottom: 5px;
      right: 10px;
      font-size: 1rem;
    }

    .star.filled {
      color: #2c75ff;
    }

    .carousel-nav {
      position: absolute;
      bottom: 5px;
      width: 100%;
      text-align: center;
    }

    .nav-dot {
      display: inline-block;
      width: 10px;
      height: 10px;
      margin: 0 5px;
      background: #d2d1d1;
      border-radius: 50%;
      cursor: pointer;
    }

    .nav-dot.active {
      background: #2c75ff;
    }

    .categorie-link-main {
      display: inline-block;
      padding: 5px;
      margin-top: 30px;
      margin-bottom: 10px;
      color: white;
      text-decoration: none;
      font-weight: bold;
      margin-left: 230px;
      transition: color 0.2s;
      font-size: 20px;
    }
        
    .categorie-link-main:hover {
      color: #2c75ff;
    }
        
    .text-with-image {
      display: flex;
      color: white;
    }

    </style>
    <title>Conseils en Mathématiques</title>
</head>
<body>
    </br>
    <div class="line" style="width: 5%; padding: 1px;"></div>
    </br></br></br></br></br>
    <div class="text-with-image" style="justify-content: left; padding-left: 25%;">
        <img src="Images/bulle.png" alt="Bulle de décoration" height="30" style="margin-top: 14px; margin-right: 4px;">
        <h3>RETROUVEZ LES MEILLEURS CONSEILS DE LA COMMUNAUTE</h3>
    </div>

    <div class="text-with-image">
        <span class="categorie-link-main">
            <a href="Conseil/Mathématique/page/1.php" class="categorie-link-main" style="padding-right: 0%;" target="_parent">Mathématique</a>
        </span>
    </div>
    <div class="carousel">
        <div class="carousel-container" id="carousel1">
            <?php echo $html_conseils_mathematique; ?>
        </div>
        <div class="carousel-nav">
            <span class="nav-dot" data-index="0"></span>
            <span class="nav-dot" data-index="1"></span>
            <span class="nav-dot" data-index="2"></span>
        </div>
    </div>
    <div class="line" style="width: 51.5%;"></div>

    <div class="text-with-image">
        <span class="categorie-link-main">
            <a href="Conseil/SVT/page/1.php" class="categorie-link-main" style="padding-right: 0%;" target="_parent">SVT</a>
        </span>
    </div>
    <div class="carousel">
        <div class="carousel-container" id="carousel2">
            <?php echo $html_conseils_svt; ?>
        </div>
        <div class="carousel-nav">
            <span class="nav-dot" data-index="0"></span>
            <span class="nav-dot" data-index="1"></span>
            <span class="nav-dot" data-index="2"></span>
        </div>
    </div>
    <div class="line" style="width: 51.5%;"></div>
    
    <div class="text-with-image">
        <span class="categorie-link-main">
            <a href="Conseil/Physique/page/1.php" class="categorie-link-main" style="padding-right: 0%;" target="_parent">Physique</a>
        </span>
    </div>
    <div class="carousel">
        <div class="carousel-container" id="carousel3">
            <?php echo $html_conseils_physique; ?>
        </div>
        <div class="carousel-nav">
            <span class="nav-dot" data-index="0"></span>
            <span class="nav-dot" data-index="1"></span>
            <span class="nav-dot" data-index="2"></span>
        </div>
    </div>
    <div class="line" style="width: 51.5%;"></div>

    <div class="text-with-image">
        <span class="categorie-link-main">
            <a href="Conseil/Français/page/1.php" class="categorie-link-main" style="padding-right: 0%;" target="_parent">Français</a>
        </span>
    </div>
    <div class="carousel">
        <div class="carousel-container" id="carousel4">
            <?php echo $html_conseils_francais; ?>
        </div>
        <div class="carousel-nav">
            <span class="nav-dot" data-index="0"></span>
            <span class="nav-dot" data-index="1"></span>
            <span class="nav-dot" data-index="2"></span>
        </div>
    </div>
    <div class="line" style="width: 51.5%;"></div>

    <div class="text-with-image">
        <span class="categorie-link-main">
            <a href="Conseil/Philosophie/page/1.php" class="categorie-link-main" style="padding-right: 0%;" target="_parent">Philosophie</a>
        </span>
    </div>
    <div class="carousel">
        <div class="carousel-container" id="carousel5">
            <?php echo $html_conseils_philosophie; ?>
        </div>
        <div class="carousel-nav">
            <span class="nav-dot" data-index="0"></span>
            <span class="nav-dot" data-index="1"></span>
            <span class="nav-dot" data-index="2"></span>
        </div>
    </div>
    <div class="line" style="width: 51.5%;"></div>

    <div class="text-with-image">
        <span class="categorie-link-main">
            <a href="Conseil/Anglais/page/1.php" class="categorie-link-main" style="padding-right: 0%;" target="_parent">Anglais</a>
        </span>
    </div>
    <div class="carousel">
        <div class="carousel-container" id="carousel6">
            <?php echo $html_conseils_anglais; ?>
        </div>
        <div class="carousel-nav">
            <span class="nav-dot" data-index="0"></span>
            <span class="nav-dot" data-index="1"></span>
            <span class="nav-dot" data-index="2"></span>
        </div>
    </div>
    <div class="line" style="width: 51.5%;"></div>
    
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const carousels = document.querySelectorAll('.carousel');

            carousels.forEach(carousel => {
                const container = carousel.querySelector('.carousel-container');
                const cards = carousel.querySelectorAll('.card-conseils');
                const dots = carousel.querySelectorAll('.nav-dot');
                let currentIndex = 0;
                const cardWidth = cards[0].offsetWidth; 
                const cardMargin = 10;

                function updateCarousel() {
                    const newPosition = -(currentIndex * (cardWidth + cardMargin));
                    container.style.transform = `translateX(${newPosition}px)`;
                    dots.forEach(dot => dot.classList.remove('active'));
                    dots[currentIndex].classList.add('active');
                }

                dots.forEach((dot, index) => {
                    dot.addEventListener('click', () => {
                        currentIndex = index;
                        updateCarousel();
                    });
                });

                updateCarousel();
            });
        });
    </script>
</body>
</html>
