<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ÉduConseils - Français</title>
    <link rel="stylesheet" href="../../../style.css">
</head>

<iframe src="../../../nav.php" width="100%" height="127px" frameborder="0"></iframe>

<body>
    <main>
        </br>
        </br>
        <div class="text-with-image" style="justify-content: left; padding-left: 25%;">
            <img src="../../../Images/bulle.png" alt="Bulle de décoration" height="30" style="margin-top: 14px; margin-right: 4px;">
            <h3>RETROUVEZ LES CONSEILS LES PLUS RECENT DE LA COMMUNAUTE EN FRANCAIS</h3>
        </div>
        </br>
        <div class="vignettes-container">
            <?php
            $options = [
                'page_number' => 2,
                'page_matiere' => 'Français'
            ];
            include '../../backend.php';
            ?>
        </div>
        </br>
        <div class="pagination">
            <a href="1.php" class="pagination-link">&laquo; Précédent</a>
            <a href="1.php" class="pagination-link">1</a>
            <a href="2.php" class="pagination-link active">2</a>
            <a href="2.php" class="pagination-link">Suivant &raquo;</a>
        </div>
    </main>
    <script src="script.js"></script>
</body>
<footer>
    <iframe src="../../../section.html" frameborder="0" width="100%" height="100%"></iframe>
    <iframe src="../../../footer.html" width="100%" height="100%" frameborder="0"></iframe>
</footer>

</html>
