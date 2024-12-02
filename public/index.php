<?php
include '../templates/header.php';
?>

<h1>Bienvenue sur la plateforme de gestion de scrutins</h1>
<p>Participez à des scrutins en ligne, créez des consultations, et plus encore.</p>

<!-- Liens vers l'inscription et la connexion -->
<?php if (!isset($_SESSION['user_id'])): ?>
    <p>
        <a href="register.php">S'inscrire</a> | 
        <a href="login.php">Se connecter</a>
    </p>
<?php else: ?>
    <p>Bienvenue, <?php echo htmlspecialchars($_SESSION['pseudo']); ?> !</p>
    <p><a href="dashboard.php">Accéder au tableau de bord</a></p>
<?php endif;
include '../templates/footer.php'; ?>