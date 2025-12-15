<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: http://localhost:8083/"); 
    exit();
}

// Vérifier si l'ID de la relation_actp_resid_med a été transmis
if (!isset($_GET['id_relation'])) {
    echo "ID de la relation non spécifié.";
    exit();
}

$id_relation = $_GET['id_relation'];


$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "teste";


$conn = mysqli_connect($servername, $username, $password, $dbname);


if (!$conn) {
    die("Échec de la connexion : " . mysqli_connect_error());
}

// Récupérer l'ID de l'utilisateur connecté
$username = $_SESSION['username'];
$query = "SELECT id FROM members WHERE username = '$username'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $userId = $row['id'];

    // Mettre à jour la colonne "medecin_valid" uniquement pour la ligne correspondant à l'ID de la relation
    $updateQuery = "UPDATE relation_actp_resid_med SET medecin_valid = $userId WHERE id_rel = $id_relation";
    if (mysqli_query($conn, $updateQuery)) {
        echo "Validation de l'activité pratique réussie.";
    } else {
        echo "Erreur lors de la validation de l'activité pratique : " . mysqli_error($conn);
    }
} else {
    echo "Utilisateur non trouvé.";
}

mysqli_close($conn);
?>
