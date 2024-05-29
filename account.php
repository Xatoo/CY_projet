<?php
// Démarrer la session PHP
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    header("Location: login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compte utilisateur</title>
    <link rel="stylesheet" href="style.css">
</head>

<body style="background-color: white">
    <div class="container">
        <?php
        // Ouvrir le fichier CSV des utilisateurs
        $fichier_utilisateurs = fopen("Données/utilisateur.csv", "r");

        // Définir le chemin du fichier CSV des conseils
        $fichier_conseils = 'Données/conseil.csv';

        // Initialiser la variable pour indiquer si l'utilisateur est trouvé
        $utilisateur_trouve = false;

        // Récupérer l'ID de l'utilisateur connecté
        $self_id = $_SESSION['id'];

        // Récupérer l'ID de l'utilisateur à afficher
        $id_utilisateur = isset($_GET['id']) ? htmlspecialchars($_GET['id']) : 'ID not set';

        // Parcourir le fichier des utilisateurs pour trouver les informations de l'utilisateur
        while (($ligne = fgetcsv($fichier_utilisateurs)) !== false) {
            if ($ligne[0] == $id_utilisateur) {
                // Si l'utilisateur est trouvé, assigner ses informations aux variables
                $utilisateur_trouve = true;
                $user = $ligne[1];
                $nom = $ligne[3];
                $prenom = $ligne[4];
                break;
            }
        }

        // Fermer le fichier des utilisateurs
        fclose($fichier_utilisateurs);

        // Définir la fonction pour obtenir le nombre de conseils d'un utilisateur
        function getNombreConseilsUser($id_utilisateur) {
            // Initialiser le nombre de conseils
            $nb_conseil = 0;

            // Ouvrir le fichier CSV des conseils
            $fichier_conseil = fopen("Données/conseil.csv", "r");

            // Parcourir le fichier des conseils pour compter les conseils de l'utilisateur
            while (($ligne_conseil = fgetcsv($fichier_conseil)) !== false) {
                // Vérifier si l'ID de l'auteur du conseil correspond à l'ID de l'utilisateur
                if ($ligne_conseil[4] == $id_utilisateur) {
                    $nb_conseil++;
                }
            }

            // Fermer le fichier des conseils
            fclose($fichier_conseil);

            return $nb_conseil;
        }
    
        function getMeilleurConseilUser($id_utilisateur) {
            // Initialisation du meilleur conseil et de sa note
            $meilleur_conseil = null;
            $meilleur_note = 0;

            // Ouvrir le fichier CSV des conseils
            $fichier_conseil = fopen("Données/conseil.csv", "r");

            // Parcourir le fichier pour trouver le meilleur conseil
            while (($ligne_conseil = fgetcsv($fichier_conseil)) !== false) {
                // Vérifier si l'auteur du conseil est l'utilisateur spécifié
                if ($ligne_conseil[4] == $id_utilisateur) {
                    // Récupérer l'ID du conseil
                    $id_conseil = $ligne_conseil[0];

                    // Ouvrir le fichier CSV des notes
                    $fichier_note = fopen("Données/note.csv", "r");

                    // Initialiser la note du conseil
                    $note_conseil = 0;

                    // Parcourir le fichier des notes pour trouver la note du conseil
                    while (($ligne_note = fgetcsv($fichier_note)) !== false) {
                        // Vérifier si l'ID du conseil dans la note correspond à l'ID du conseil en cours
                        if ($ligne_note[1] == $id_conseil) {
                            // Vérifier si l'utilisateur de la note est différent de l'utilisateur spécifié
                            if ($ligne_note[0] != $id_utilisateur) {
                                // Mettre à jour la note du conseil
                                $note_conseil += intval($ligne_note[2]);
                            }
                        }
                    }

                    // Fermer le fichier des notes
                    fclose($fichier_note);

                    // Mettre à jour le meilleur conseil si la note est la meilleure jusqu'à présent
                    if ($note_conseil >= $meilleur_note) {
                        $meilleur_note = $note_conseil;
                        $meilleur_conseil = $ligne_conseil[1];
                    }
                }
            }

            // Fermer le fichier des conseils
            fclose($fichier_conseil);

            return $meilleur_conseil;
        }
        
        // Utiliser la fonction pour obtenir le nombre de conseils de l'utilisateur du profil
        $nb_conseil = getNombreConseilsUser($id_utilisateur);
        
        // Utilisation de la fonction pour obtenir le meilleur conseil de l'utilisateur du profil
        $meilleur_conseil = getMeilleurConseilUser($id_utilisateur);
        
        // Afficher les informations de l'utilisateur si trouvé, sinon afficher un message d'erreur
        if ($utilisateur_trouve) {
        ?>
            <div class='userinfo'>           
                <p><strong>Login utilisateur:</strong> <?php echo $user; ?></p>
                <p><strong>Nom:</strong> <?php echo $nom; ?></p>
                <p><strong>Prénom:</strong> <?php echo $prenom; ?></p>
                <p><strong>Nombre de conseils:</strong> <?php echo $nb_conseil; ?></p>

                <?php if ($meilleur_conseil !== null) { ?>
                    <p><strong>Meilleur conseil:</br></strong> <?php echo $meilleur_conseil; ?></p>
                <?php } else { ?>
                    <p><strong>Aucun conseil trouvé.</strong></p>
                    <p>Il est temps de poster !</p>
                <?php } ?>

                <?php
                // Vérifier si l'utilisateur trouvé est l'utilisateur connecté
                if ($self_id == $id_utilisateur) {
                ?>
                    <br><br>
                    <a href="change-password.html" target="_parent" style="text-align: center;">Changer votre mot de passe</a>
                    </br></br>
                    <form action="logout.php" method="post" target="_parent">
                        <button type="submit" class="submit">Déconnexion</button>
                    </form>
                <?php
                }
                ?>
            </div>
        <?php
        } else {
            echo "<p>Utilisateur non trouvé.</p>";
        }
        ?>
    </div>
</body>

</html>
