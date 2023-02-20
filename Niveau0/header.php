<?php
        session_start();
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) { 
            session_destroy();
            header('Refresh:0');
        }
?>
<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>ReSoC - Flux</title>         
        <meta name="author" content="Julien Falconnet">
        <link rel="stylesheet" href="style.css"/>
    </head>
    <body>
        <header>
            <img src="logo-yoi-basho.png" alt="Logo de notre réseau social"/>
            <nav id="menu">
            <?php if (isset($_SESSION['connected_id'])) { ?>
                <form method="post">
                <a href="news.php">Actualités</a>
                <a href="wall.php?user_id=<?php echo $_SESSION['connected_id']?>">Mur</a>
                <a href="feed.php?user_id=<?php echo $_SESSION['connected_id']?>">Flux</a>
                <a href="tags.php?tag_id=1">Mots-clés</a>
                </form>
            <?php } ?>
            <?php if (!isset($_SESSION['connected_id'])) { ?>
                <a href="login.php">Login</a>
            <?php } ?>
            </nav>
            <?php if (isset($_SESSION['connected_id'])) { ?>
            <nav id="user">
                <a href="#">Profil</a>
                <ul>
                    <li><a href="settings.php?user_id=<?php echo $_SESSION['connected_id']?>">Paramètres</a></li>
                    <li><a href="followers.php?user_id=<?php echo $_SESSION['connected_id']?>">Mes suiveurs</a></li>
                    <li><a href="subscriptions.php?user_id=<?php echo $_SESSION['connected_id']?>">Mes abonnements</a></li>
                    <form method="post">
                    <button type="sumbit" name="logout">Logout</button>
                    </form>
                </ul>

            </nav>
            <?php } ?>
        </header>