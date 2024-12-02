<?php
// Inclure la connexion à la base de données
include_once '../src/config/dbconnection.php';
include '../templates/header.php';

// Vérifier si le formulaire d'inscription est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pseudo = $_POST['pseudo'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Vérifier que les mots de passe correspondent
    if ($password !== $confirm_password) {
        echo "Les mots de passe ne correspondent pas.";
        exit();
    }

    // Hacher le mot de passe pour sécuriser les données
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $database = new Database();
    $conn = $database->getConnection();

    try {
        // Insérer l'utilisateur dans la base de données
        $query = "INSERT INTO users (pseudo, email, password) VALUES (:pseudo, :email, :password)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':pseudo', $pseudo);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->execute();

        echo "Inscription réussie ! Redirection vers la page de connexion...";
        header("Refresh: 2; url=login.php");
        exit();
    } catch (PDOException $e) {
        echo "Erreur lors de l'inscription : " . $e->getMessage();
    }
}
?>

<!-- Formulaire d'inscription -->
<h1>Inscription</h1>
<form method="POST" action="register.php">
    <label>Pseudo :</label>
    <input type="text" name="pseudo" required><br>

    <label>Email :</label>
    <input type="email" name="email" required><br>

    <label>Mot de passe :</label>
    <input type="password" name="password" required><br>

    <label>Confirmer le mot de passe :</label>
    <input type="password" name="confirm_password" required><br>

    <input type="submit" value="S'inscrire">
</form>

<?php include '../templates/footer.php'; ?>