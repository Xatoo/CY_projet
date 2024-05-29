<?php
session_start(); // Démarre la session
session_unset(); // Supprime toutes les variables de session
session_destroy(); // Détruit la session
header("Location: index.html"); // Redirige vers la page d'accueil ou une autre page
exit;
?>
