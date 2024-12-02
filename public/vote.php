<?php
// Inclure la connexion à la base de données
include_once '../src/config/dbconnection.php';
include '../templates/header.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    echo "Vous devez être connecté pour voter.";
    exit();
}

$id_scrutin = $_GET['id'];

$database = new Database();
$conn = $database->getConnection();

// Vérifier si l'utilisateur a déjà voté
$query_check_vote = "SELECT * FROM votes WHERE id_scrutin = :id_scrutin AND id_user = :id_user";
$stmt_check_vote = $conn->prepare($query_check_vote);
$stmt_check_vote->bindParam(':id_scrutin', $id_scrutin);
$stmt_check_vote->bindParam(':id_user', $_SESSION['user_id']);
$stmt_check_vote->execute();

if ($stmt_check_vote->rowCount() > 0) {
    echo "Vous avez déjà voté pour ce scrutin. Vous ne pouvez pas voter deux fois.";
    include '../templates/footer.php';
    exit();
}

// Récupérer les informations du scrutin
$query_scrutin = "SELECT * FROM scrutins WHERE id = :id_scrutin";
$stmt_scrutin = $conn->prepare($query_scrutin);
$stmt_scrutin->bindParam(':id_scrutin', $id_scrutin);
$stmt_scrutin->execute();
$scrutin = $stmt_scrutin->fetch(PDO::FETCH_ASSOC);

// Récupérer les options du scrutin
$query_options = "SELECT * FROM options WHERE id_scrutin = :id_scrutin";
$stmt_options = $conn->prepare($query_options);
$stmt_options->bindParam(':id_scrutin', $id_scrutin);
$stmt_options->execute();
$options = $stmt_options->fetchAll(PDO::FETCH_ASSOC);

// Si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $choix = $_POST['choix']; // Pour Condorcet : tableau des choix classés

    try {
        // Enregistrement du vote
        $query_vote = "INSERT INTO votes (id_user, id_scrutin, choix) VALUES (:id_user, :id_scrutin, :choix)";
        $stmt_vote = $conn->prepare($query_vote);
        $stmt_vote->bindParam(':id_user', $_SESSION['user_id']);
        $stmt_vote->bindParam(':id_scrutin', $id_scrutin);

        // Gestion des différentes méthodes de vote
        if ($scrutin['methode_de_vote'] == 'proportionnel' || $scrutin['methode_de_vote'] == 'majoritaire') {
            // Vote proportionnel ou majoritaire : choix unique
            $stmt_vote->bindParam(':choix', $choix); // Stocker l'option choisie
        } elseif ($scrutin['methode_de_vote'] == 'condorcet') {
            // Vote Condorcet : classement des options
            $stmt_vote->bindParam(':choix', json_encode($choix)); // Stocker le classement en JSON
        }

        $stmt_vote->execute();
        echo "Votre vote a été soumis avec succès !";
    } catch (PDOException $e) {
        echo "Erreur lors de la soumission du vote : " . $e->getMessage();
    }
}
?>

<!-- Afficher le formulaire de vote en fonction de la méthode de vote -->
<h1><?php echo htmlspecialchars($scrutin['question']); ?></h1>

<form id="voteForm" method="POST" action="vote.php?id=<?php echo $id_scrutin; ?>">

    <?php if ($scrutin['methode_de_vote'] == 'proportionnel'): ?>
        <!-- Affichage pour le vote proportionnel (choix unique) -->
        <?php foreach ($options as $option): ?>
            <label><?php echo htmlspecialchars($option['option_text']); ?></label>
            <input type="radio" name="choix" value="<?php echo htmlspecialchars($option['option_text']); ?>" required><br>
        <?php endforeach; ?>

    <?php elseif ($scrutin['methode_de_vote'] == 'majoritaire'): ?>
        <!-- Affichage pour le vote majoritaire (choix unique) -->
        <?php foreach ($options as $option): ?>
            <label><?php echo htmlspecialchars($option['option_text']); ?></label>
            <input type="radio" name="choix" value="<?php echo htmlspecialchars($option['option_text']); ?>" required><br>
        <?php endforeach; ?>

    <?php elseif ($scrutin['methode_de_vote'] == 'condorcet'): ?>
        <!-- Affichage pour le vote Condorcet (classement) -->
        <p>Classez les options par ordre de préférence (1 étant le meilleur choix) :</p>
        <?php foreach ($options as $option): ?>
            <label><?php echo htmlspecialchars($option['option_text']); ?></label>
            <input type="number" name="choix[<?php echo htmlspecialchars($option['option_text']); ?>]" min="1" max="<?php echo count($options); ?>" required><br>
        <?php endforeach; ?>
    <?php endif; ?>

    <input type="button" value="Soumettre le vote" onclick="confirmVote()">
</form>

<!-- Popup d'avertissement avec JavaScript -->
<script>
function confirmVote() {
    var confirmation = confirm("Êtes-vous sûr de votre choix ? Une fois soumis, vous ne pourrez pas le changer.");
    if (confirmation) {
        document.getElementById("voteForm").submit();
    }
}
</script>

<!-- Bouton de retour au tableau de bord -->
<p><a href="dashboard.php" class="button">Retour au tableau de bord</a></p>

<?php include '../templates/footer.php'; ?>
