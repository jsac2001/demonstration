<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de Scrutins</title>
    <!-- Inclure votre CSS ici -->
    <link rel="stylesheet" href="path_to_your_css.css">
</head>
<body>

<!-- Barre de navigation -->
<header>
    <nav>
        <ul>
            <li><a href="index.php">Accueil</a></li>
            <?php if (!isset($_SESSION['user'])): ?>
                <li><a href="register.php">S'inscrire</a></li>
                <li><a href="login.php">Se connecter</a></li>
            <?php else: ?>
                <li><a href="dashboard.php">Tableau de bord</a></li>
                <li><a href="logout.php">Se déconnecter</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<main>
