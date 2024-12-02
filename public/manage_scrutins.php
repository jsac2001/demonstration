<?php
// Inclure la connexion à la base de données
include_once '../src/config/dbconnection.php';
include '../templates/header.php';

// Vérifier si l'utilisateur est administrateur ou organisateur
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'organisateur')) {
    echo "Vous n'avez pas les droits pour gérer les scrutins.";
    exit();
}

$database = new Database();
$conn = $database->getConnection();

// Supprimer un scrutin
if (isset($_POST['delete_scrutin'])) {
    $scrutin_id = $_POST['scrutin_id'];
    $query_delete = "DELETE FROM scrutins WHERE id = :scrutin_id";
    $stmt_delete = $conn->prepare($query_delete);
    $stmt_delete->bindParam(':scrutin_id', $scrutin_id);
    $stmt_delete->execute();
    echo "Scrutin supprimé avec succès.";
}

// Récupérer la liste des scrutins
$query_scrutins = "SELECT * FROM scrutins";
$stmt_scrutins = $conn->prepare($query_scrutins);
$stmt_scrutins->execute();
$scrutins = $stmt_scrutins->fetchAll(PDO::FETCH_ASSOC);
?>

<h1>Gestion des scrutins</h1>

<table border="1">
    <tr>
        <th>ID</th>
        <th>Question</th>
        <th>Date de début</th>
        <th>Date de fin</th>
        <th>Méthode de vote</th>
        <th>Action</th>
    </tr>
    <?php foreach ($scrutins as $scrutin): ?>
        <tr>
            <td><?php echo htmlspecialchars($scrutin['id']); ?></td>
            <td><?php echo htmlspecialchars($scrutin['question']); ?></td>
            <td><?php echo htmlspecialchars($scrutin['date_debut']); ?></td>
            <td><?php echo htmlspecialchars($scrutin['date_fin']); ?></td>
            <td><?php echo htmlspecialchars($scrutin['methode_de_vote']); ?></td>
            <td>
                <form method="POST" action="manage_scrutins.php">
                    <input type="hidden" name="scrutin_id" value="<?php echo $scrutin['id']; ?>">
                    <input type="submit" name="delete_scrutin" value="Supprimer">
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<?php include '../templates/footer.php'; ?>
