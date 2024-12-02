<?php
// Inclure la connexion à la base de données
include_once '../src/config/dbconnection.php';
include '../templates/header.php';

// Vérifier si le formulaire de connexion est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $database = new Database();
    $conn = $database->getConnection();

    // Récupérer l'utilisateur en fonction de l'email
    $query = "SELECT * FROM users WHERE email = :email";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérifier si l'utilisateur existe et si le mot de passe est correct
    if ($user && password_verify($password, $user['password'])) {
        // Initialiser la session après vérification du mot de passe
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['pseudo'] = $user['pseudo'];
        $_SESSION['role'] = $user['role']; // Rôle de l'utilisateur, par exemple 'admin' ou 'participant'
    
        echo "Connexion réussie ! Redirection vers le tableau de bord...";
        header("Refresh: 2; url=dashboard.php");
        exit();
    } else {
        echo "Identifiants incorrects.";
    }
    
}
?>

<!-- Formulaire de connexion -->
<h1>Connexion</h1>
<form method="POST" action="login.php">
    <label>Email :</label>
    <input type="email" name="email" required><br>

    <label>Mot de passe :</label>
    <input type="password" name="password" required><br>

    <input type="submit" value="Se connecter">
</form>

<?php include '../templates/footer.php'; ?>