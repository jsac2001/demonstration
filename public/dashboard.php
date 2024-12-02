<?php
// Inclure la connexion à la base de données
include_once '../src/config/dbconnection.php';
include '../templates/header.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    echo "Vous devez être connecté pour accéder à votre tableau de bord.";
    exit();
}

$database = new Database();
$conn = $database->getConnection();

// Récupérer les scrutins en cours
$query_scrutins_ouverts = "SELECT * FROM scrutins WHERE date_debut <= NOW() AND date_fin >= NOW()";
$stmt_scrutins_ouverts = $conn->prepare($query_scrutins_ouverts);
$stmt_scrutins_ouverts->execute();
$scrutins_ouverts = $stmt_scrutins_ouverts->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les scrutins terminés pour voir les résultats
$query_scrutins_termines = "SELECT * FROM scrutins WHERE date_fin < NOW()";
$stmt_scrutins_termines = $conn->prepare($query_scrutins_termines);
$stmt_scrutins_termines->execute();
$scrutins_termines = $stmt_scrutins_termines->fetchAll(PDO::FETCH_ASSOC);
?>

<h1>Tableau de bord</h1>

<p>Bienvenue, <?php echo htmlspecialchars($_SESSION['pseudo']); ?> !</p>
<p><a href="logout.php">Se déconnecter</a></p>

<!-- Bouton pour créer un nouveau scrutin si l'utilisateur est admin ou organisateur -->
<?php if ($_SESSION['role'] === 'organisateur' || $_SESSION['role'] === 'admin'): ?>
    <p><a href="create_scrutin.php">Créer un nouveau scrutin</a></p>
<?php endif; ?>

<h2>Scrutins en cours</h2>
<?php if (count($scrutins_ouverts) > 0): ?>
    <ul>
    <?php foreach ($scrutins_ouverts as $scrutin): ?>
        <li>
            <a href="vote.php?id=<?php echo $scrutin['id']; ?>">
                <?php echo htmlspecialchars($scrutin['question']); ?>
            </a>
            (Fin : <?php echo htmlspecialchars($scrutin['date_fin']); ?>)
        </li>
    <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Aucun scrutin en cours.</p>
<?php endif; ?>

<h2>Scrutins terminés</h2>
<?php if (count($scrutins_termines) > 0): ?>
    <ul>
    <?php foreach ($scrutins_termines as $scrutin): ?>
        <li>
            <a href="results.php?id=<?php echo $scrutin['id']; ?>">
                <?php echo htmlspecialchars($scrutin['question']); ?>
            </a>
            (Terminé le : <?php echo htmlspecialchars($scrutin['date_fin']); ?>)
        </li>
    <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Aucun scrutin terminé.</p>
<?php endif; ?>

<?php include '../templates/footer.php'; ?>
