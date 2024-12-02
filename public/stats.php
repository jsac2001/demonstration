<?php
// Inclure la connexion à la base de données
include_once '../src/config/dbconnection.php';
include '../templates/header.php';

// Vérifier si l'utilisateur est un administrateur
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    echo "Vous n'avez pas les droits pour consulter les statistiques.";
    exit();
}

$database = new Database();
$conn = $database->getConnection();

// Récupérer le nombre total de scrutins créés
$query_scrutins_total = "SELECT COUNT(*) as total_scrutins FROM scrutins";
$stmt_scrutins_total = $conn->prepare($query_scrutins_total);
$stmt_scrutins_total->execute();
$total_scrutins = $stmt_scrutins_total->fetch(PDO::FETCH_ASSOC)['total_scrutins'];

// Récupérer le nombre total d'utilisateurs inscrits
$query_users_total = "SELECT COUNT(*) as total_users FROM users";
$stmt_users_total = $conn->prepare($query_users_total);
$stmt_users_total->execute();
$total_users = $stmt_users_total->fetch(PDO::FETCH_ASSOC)['total_users'];

// Récupérer le nombre total de votes soumis
$query_votes_total = "SELECT COUNT(*) as total_votes FROM votes";
$stmt_votes_total = $conn->prepare($query_votes_total);
$stmt_votes_total->execute();
$total_votes = $stmt_votes_total->fetch(PDO::FETCH_ASSOC)['total_votes'];

// Calculer le taux de participation moyen (nombre de votes par scrutin)
$taux_participation_moyen = $total_scrutins > 0 ? round($total_votes / $total_scrutins, 2) : 0;
?>

<h1>Statistiques de la plateforme</h1>

<p><strong>Total des scrutins créés :</strong> <?php echo $total_scrutins; ?></p>
<p><strong>Total des utilisateurs inscrits :</strong> <?php echo $total_users; ?></p>
<p><strong>Total des votes soumis :</strong> <?php echo $total_votes; ?></p>
<p><strong>Taux de participation moyen par scrutin :</strong> <?php echo $taux_participation_moyen; ?></p>

<?php include '../templates/footer.php'; ?>