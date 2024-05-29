<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ma Page Navigation</title>
    <style>
        
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

        nav {
            background-color: #f4f4f4;
            text-align: center;
            margin: 0;
        }
        
        .top-nav {
          background-color: #2c75ff;
          padding: 18px;
          text-align: center;
          font-weight: bold;
        }

        .button-box-top-nav {
          position: relative;
          display: inline-block;
          padding: 8px;
          color: white;
          text-decoration: none;
          font-weight: bold;
        }

        .button-box-top-nav::after {
          content: '';
          position: absolute;
          width: 0px;
          height: 2px;
          background-color: #1a2226;
          bottom: 0;
          left: 0;
          transition: 0.3s;
          transform: translateX(25%);
        }

        .button-box-top-nav:hover::after {
          transform: translateX(0);
          width: 100%;
          left: 0;
        }

        .top-right-nav {
          position: absolute;
          padding: 18px;
          top: 0;
          right: 0;
        }

        /* -------- FIN TOP NAV -------- */

        /* -------- DEBUT BOTTOM NAV -------- */

        .bottom-nav {
          background-color: white;
          color: white;
          padding: 10px;
          text-align: center;
        }

        .button-box-bottom-nav {
          display: inline-block;
          padding: 10px;
          margin-right: 2%;
          color: #1a2226;
          text-decoration: none;
          font-weight: bold;
          transition: color 0.2s;
          font-size: 0.8em;
        }

        .button-box-bottom-nav:hover {
          color: #2c75ff;
        }

        .bottom-right-nav {
          position: absolute;
          padding: 10px;
          top: 70px;
          right: 0px;
          text-align: center;
          white-space: nowrap;
        }

        .nav-line {
          background-color: #b3b3b3;
          padding: 0.5px;
        }
    </style>
</head>
<body> 
    <div class="top-nav">
        <span style="margin-right:5%"><a href="index.html" class="button-box-top-nav" target="_parent">Accueil</a></span>
        <span style="margin-right:5%"><a href="workinprogress.html" class="button-box-top-nav" target="_parent">Nouveautés</a></span>
        <a href="workinprogress.html" class="button-box-top-nav" target="_parent">Tendances</a>
    </div>

    <div class="top-right-nav">
        <?php if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true):
    
            // Récupération de l'ID de l'utilisateur depuis la session
            $userId = isset($_SESSION["id"]) ? $_SESSION["id"] : '';
            ?>
            <a href="account.html?id=<?php echo htmlspecialchars($userId); ?>" class="button-box-top-nav" target="_parent">Votre compte</a>
        <?php else: ?>
            <a href="login.html" class="button-box-top-nav" target="_parent">Se connecter</a>
        <?php endif; ?>
    </div>
    <div class="bottom-nav">
        <span><a href="Conseil/Seciales/Réussir son bac.html" class="button-box-bottom-nav" target="_parent">REUSSIR SON BAC</a></span>
        <span><a href="Conseil/Seciales/Réussir son brevet.html" class="button-box-bottom-nav" target="_parent">REUSSIR SON BREVET</a></span>
        <span><a href="Conseil/Seciales/Apprendre a travailler a la maison.html" class="button-box-bottom-nav" target="_parent">APPRENDRE A TRAVAILLER A LA MAISON</a></span>
    </div>

    <div class="bottom-right-nav">
        <span><a href="deposit.html" class="button-box-bottom-nav" target="_parent">DEPOSE TON CONSEIL</a></span>
    </div>
    <div class="nav-line"></div>
</body>

</html>