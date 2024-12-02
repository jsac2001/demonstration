<?php
// Inclure la connexion à la base de données
include_once '../src/config/dbconnection.php';
include '../templates/header.php';

// Vérifier si l'utilisateur est connecté et a les droits (organisateur ou admin)
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] != 'organisateur' && $_SESSION['role'] != 'admin')) {
    echo "Vous n'avez pas les droits pour créer un scrutin.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les valeurs du formulaire
    $question = $_POST['question'];
    $description = $_POST['description'];
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];
    $methode_de_vote = $_POST['methode_de_vote'];
    $options = $_POST['options']; // Array des options

    // Créer une nouvelle instance de la connexion à la base de données
    $database = new Database();
    $conn = $database->getConnection();

    try {
        // Insertion du scrutin
        $query = "INSERT INTO scrutins (question, description, date_debut, date_fin, methode_de_vote, created_by) 
                  VALUES (:question, :description, :date_debut, :date_fin, :methode_de_vote, :created_by)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':question', $question);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':date_debut', $date_debut);
        $stmt->bindParam(':date_fin', $date_fin);
        $stmt->bindParam(':methode_de_vote', $methode_de_vote);
        $stmt->bindParam(':created_by', $_SESSION['user_id']); // ID de l'utilisateur connecté
        $stmt->execute();

        // Récupérer l'ID du scrutin inséré
        $id_scrutin = $conn->lastInsertId();

        // Insérer les options
        $query_option = "INSERT INTO options (id_scrutin, option_text) VALUES (:id_scrutin, :option_text)";
        $stmt_option = $conn->prepare($query_option);
        foreach ($options as $option) {
            $stmt_option->bindParam(':id_scrutin', $id_scrutin);
            $stmt_option->bindParam(':option_text', $option);
            $stmt_option->execute();
        }

        echo "Scrutin créé avec succès !";
    } catch (PDOException $e) {
        echo "Erreur lors de la création du scrutin : " . $e->getMessage();
    }
}
?>

<!-- Formulaire de création de scrutin -->
<form method="POST" action="create_scrutin.php">
    <label>Question du scrutin :</label>
    <input type="text" name="question" required><br>

    <label>Description :</label>
    <textarea name="description"></textarea><br>

    <label>Date de début :</label>
    <input type="datetime-local" name="date_debut" required><br>

    <label>Date de fin :</label>
    <input type="datetime-local" name="date_fin" required><br>

    <label>Méthode de vote :</label>
    <select name="methode_de_vote" required>
        <option value="proportionnel">Proportionnel</option>
        <option value="majoritaire">Majoritaire</option>
        <option value="condorcet">Condorcet</option>
    </select><br>

    <label>Options de vote :</label>
    <div id="options-container">
        <input type="text" name="options[]" required><br>
    </div>
    <button type="button" onclick="addOption()">Ajouter une option</button><br><br>

    <input type="submit" value="Créer le scrutin">
</form>

<!-- Bouton de retour au tableau de bord -->
<p><a href="dashboard.php" class="button">Retour au tableau de bord</a></p>

<!-- JavaScript pour ajouter dynamiquement des champs d'option -->
<script>
function addOption() {
    var container = document.getElementById('options-container');
    var input = document.createElement('input');
    input.type = 'text';
    input.name = 'options[]';
    input.required = true;
    container.appendChild(input);
    container.appendChild(document.createElement('br'));
}
</script>

<?php include '../templates/footer.php'; ?>
