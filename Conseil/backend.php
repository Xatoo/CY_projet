<?php
// Nombre de conseils par page
$elements_par_page = 5;

// Récupérer le numéro de page et la matière depuis les options
if (isset($options['page_number']) && isset($options['page_matiere'])) {
    $page_number = $options['page_number'];
    $page_matiere = $options['page_matiere'];

    // Calculer l'index de début en fonction du numéro de page
    $index_debut = ($page_number - 1) * $elements_par_page;

    // Charger les conseils depuis le fichier CSV
    $conseils = array_map('str_getcsv', file('../../../Données/conseil.csv'));

    // Charger les utilisateurs depuis le fichier CSV
    $utilisateurs = array_map('str_getcsv', file('../../../Données/utilisateur.csv'));

    // Filtrer les conseils de la matière
    $conseils_matiere = array_filter($conseils, function($conseil) use ($page_matiere) {
        return $conseil[5] === $page_matiere;
    });

    // Trier les conseils par date décroissante
    usort($conseils_matiere, function($a, $b) {
        return strtotime($b[3]) - strtotime($a[3]);
    });

    // Filtrer les conseils correspondant à la page demandée
    $conseils_page = array_slice($conseils_matiere, $index_debut, $elements_par_page);

    // Fonction pour obtenir le prénom et le nom à partir de l'ID
    function getUserName($id, $utilisateurs) {
        foreach ($utilisateurs as $utilisateur) {
            if ($utilisateur[0] == $id) {
                return $utilisateur[3] . ' ' . $utilisateur[4]; // Concaténer prénom et nom
            }
        }
        return ''; // Retourner une chaîne vide si l'ID n'est pas trouvé
    }

    // Fonction pour générer les étoiles en fonction de la note
    function generateStars($note) {
        $html_stars = '';
        // Boucle pour générer les étoiles
        for ($i = 1; $i <= 5; $i++) {
            // Vérifier si l'étoile doit être remplie, demi-remplie ou vide
            if ($note >= $i) {
                // Étoile remplie
                $html_stars .= '<span class="vignette-star filled">&#9733;</span>';
            } elseif ($note >= $i - 0.5) {
                // Demi-étoile
                $html_stars .= '<span class="vignette-star half-filled">&#9733;</span>';
            } else {
                // Étoile vide
                $html_stars .= '<span class="vignette-star">&#9733;</span>';
            }
        }
        return $html_stars;
    }

    // Générer le code HTML pour chaque conseil
    $html_conseils = '';
    foreach ($conseils_page as $conseil) {
        // Générer le nom de fichier en utilisant l'id et le titre
        $filename = $conseil[0] . '-' . $conseil[1] . '.html';

        // Obtenir le prénom et le nom de l'utilisateur à partir de l'ID
        $userName = getUserName($conseil[4], $utilisateurs);

        // Générer les étoiles en fonction de la note du conseil
        $html_stars = generateStars($conseil[7]);

        $html_conseils .= '
        <a class="vignette" href="../' . $filename . '">
            <div class="vignette-content">
                <div class="vignette-header">
                    <div class="vignette-category">' . $conseil[5] . '</div>
                    <div class="vignette-etude">' . $conseil[6] . '</div>
                </div>
                <div class="vignette-body">
                    <div class="vignette-title">' . $conseil[1] . '</div>
                    <div class="vignette-tease-text">' . $conseil[2] . '</div>
                </div>
                <div class="vignette-footer">
                    <div class="vignette-date-name">
                        <div class="vignette-date">' . $conseil[3] . '</div>
                        <div class="vignette-name">' . $userName . '</div>
                    </div>
                    <div class="vignette-stars">
                        ' . $html_stars . '
                    </div>
                </div>
            </div>
        </a>';
    }
    
echo $html_conseils;
}
?>