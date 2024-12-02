<?php
// Inclure la connexion à la base de données
include_once '../src/config/dbconnection.php';
include '../templates/header.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    echo "Vous devez être connecté pour voir les résultats.";
    exit();
}

// Récupérer l'ID du scrutin
$id_scrutin = $_GET['id'];

$database = new Database();
$conn = $database->getConnection();

// Récupérer les informations du scrutin
$query_scrutin = "SELECT * FROM scrutins WHERE id = :id_scrutin";
$stmt_scrutin = $conn->prepare($query_scrutin);
$stmt_scrutin->bindParam(':id_scrutin', $id_scrutin);
$stmt_scrutin->execute();
$scrutin = $stmt_scrutin->fetch(PDO::FETCH_ASSOC);

// Récupérer les résultats du scrutin (choix enregistrés sous forme de JSON pour Condorcet)
$query_votes = "SELECT choix FROM votes WHERE id_scrutin = :id_scrutin";
$stmt_votes = $conn->prepare($query_votes);
$stmt_votes->bindParam(':id_scrutin', $id_scrutin);
$stmt_votes->execute();
$votes = $stmt_votes->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les options du scrutin
$query_options = "SELECT * FROM options WHERE id_scrutin = :id_scrutin";
$stmt_options = $conn->prepare($query_options);
$stmt_options->bindParam(':id_scrutin', $id_scrutin);
$stmt_options->execute();
$options = $stmt_options->fetchAll(PDO::FETCH_ASSOC);

$options_list = [];
foreach ($options as $option) {
    $options_list[] = $option['option_text'];
}

// Fonction pour calculer le gagnant Condorcet
function condorcetWinner($options, $votes) {
    $scores = [];

    // Initialiser les scores pour chaque option
    foreach ($options as $option) {
        $scores[$option] = 0;
    }

    // Comparer chaque option avec toutes les autres
    foreach ($options as $option1) {
        foreach ($options as $option2) {
            if ($option1 !== $option2) {
                $count1 = 0;
                $count2 = 0;

                // Comparer les votes entre option1 et option2
                foreach ($votes as $vote) {
                    $decoded_vote = json_decode($vote['choix'], true);
                    if ($decoded_vote[$option1] < $decoded_vote[$option2]) {
                        $count1++;
                    } elseif ($decoded_vote[$option1] > $decoded_vote[$option2]) {
                        $count2++;
                    }
                }

                // Si option1 gagne plus souvent contre option2, ajouter un point
                if ($count1 > $count2) {
                    $scores[$option1]++;
                }
            }
        }
    }

    // Trouver l'option avec le score maximum
    arsort($scores);
    return array_key_first($scores);
}

// Calculer le gagnant avec Condorcet après le vote
if ($scrutin['methode_de_vote'] === 'condorcet') {
    echo "<h2>Méthode Condorcet</h2>";

    if (!empty($votes)) {
        $gagnant = condorcetWinner($options_list, $votes);
        echo "<strong>Le gagnant selon l'algorithme de Condorcet est : " . htmlspecialchars($gagnant) . "</strong><br>";
    } else {
        echo "Aucun vote n'a encore été enregistré.<br>";
    }
} else {
    echo "L'analyse Condorcet n'est applicable que pour ce type de méthode de vote.<br>";
}

?>

<!-- Bouton de retour au tableau de bord -->
<p><a href="dashboard.php" class="button">Retour au tableau de bord</a></p>

<?php include '../templates/footer.php'; ?>
