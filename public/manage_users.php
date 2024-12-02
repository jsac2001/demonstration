<?php
// Inclure la connexion à la base de données
include_once '../src/config/dbconnection.php';
include '../templates/header.php';

// Vérifier si l'utilisateur est un administrateur
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    echo "Vous n'avez pas les droits pour gérer les utilisateurs.";
    exit();
}

$database = new Database();
$conn = $database->getConnection();

// Supprimer un utilisateur
if (isset($_POST['delete_user'])) {
    $user_id = $_POST['user_id'];
    $query_delete = "DELETE FROM users WHERE id = :user_id";
    $stmt_delete = $conn->prepare($query_delete);
    $stmt_delete->bindParam(':user_id', $user_id);
    $stmt_delete->execute();
    echo "Utilisateur supprimé avec succès.";
}

// Modifier le rôle d'un utilisateur
if (isset($_POST['update_role'])) {
    $user_id = $_POST['user_id'];
    $new_role = $_POST['role'];
    $query_update = "UPDATE users SET role = :role WHERE id = :user_id";
    $stmt_update = $conn->prepare($query_update);
    $stmt_update->bindParam(':role', $new_role);
    $stmt_update->bindParam(':user_id', $user_id);
    $stmt_update->execute();
    echo "Rôle mis à jour avec succès.";
}

// Récupérer la liste des utilisateurs
$query_users = "SELECT * FROM users";
$stmt_users = $conn->prepare($query_users);
$stmt_users->execute();
$users = $stmt_users->fetchAll(PDO::FETCH_ASSOC);
?>

<h1>Gestion des utilisateurs</h1>

<table border="1">
    <tr>
        <th>ID</th>
        <th>Pseudo</th>
        <th>Email</th>
        <th>Rôle</th>
        <th>Action</th>
    </tr>
    <?php foreach ($users as $user): ?>
        <tr>
            <td><?php echo htmlspecialchars($user['id']); ?></td>
            <td><?php echo htmlspecialchars($user['pseudo']); ?></td>
            <td><?php echo htmlspecialchars($user['email']); ?></td>
            <td>
                <form method="POST" action="manage_users.php">
                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                    <select name="role">
                        <option value="admin" <?php if ($user['role'] == 'admin') echo 'selected'; ?>>Admin</option>
                        <option value="organisateur" <?php if ($user['role'] == 'organisateur') echo 'selected'; ?>>Organisateur</option>
                        <option value="participant" <?php if ($user['role'] == 'participant') echo 'selected'; ?>>Participant</option>
                    </select>
                    <input type="submit" name="update_role" value="Modifier le rôle">
                </form>
            </td>
            <td>
                <form method="POST" action="manage_users.php">
                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                    <input type="submit" name="delete_user" value="Supprimer">
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<?php include '../templates/footer.php'; ?>