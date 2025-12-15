<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: http://localhost:8083/"); 
    exit();
}


$servername = "localhost"; 
$username = "root"; 
$password = "root"; 
$dbname = "teste"; 


$conn = mysqli_connect($servername, $username, $password, $dbname);


if (!$conn) {
    die("Échec de la connexion : " . mysqli_connect_error());
}

// Récupération de l'ID de l'utilisateur à partir de la table "members" en fonction du nom d'utilisateur de la session
$username_session = $_SESSION['username'];
$sql_get_user_id = "SELECT id FROM members WHERE username = ?";
$stmt_get_user_id = mysqli_prepare($conn, $sql_get_user_id);
mysqli_stmt_bind_param($stmt_get_user_id, "s", $username_session);
mysqli_stmt_execute($stmt_get_user_id);
mysqli_stmt_bind_result($stmt_get_user_id, $user_id);
mysqli_stmt_fetch($stmt_get_user_id);
// Fermer le résultat de la requête SELECT
mysqli_stmt_close($stmt_get_user_id);

// Préparation de la requête SQL avec une requête préparée pour insérer une demande de congé
$sql_insert_demande_conge = "INSERT INTO demandes_conge (date_debut, date_fin, justificatif, id_resid) VALUES (?, ?, ?, ?)";
$stmt_insert_demande_conge = mysqli_prepare($conn, $sql_insert_demande_conge);

// Liaison des paramètres
mysqli_stmt_bind_param($stmt_insert_demande_conge, "sssi", $date_debut, $date_fin, $justificatif, $user_id);

// Récupération des données du formulaire
$date_debut = $_POST['date_debut'];
$date_fin = $_POST['date_fin'];
$justificatif = $_POST['justificatif'];



// Exécution de la requête préparée pour insérer une demande de congé
if (mysqli_stmt_execute($stmt_insert_demande_conge)) {
    //echo "Demande de congé enregistrée avec succès.";
    $id_nouvelle_demande = mysqli_insert_id($conn); // Récupérer l'ID de la dernière ligne insérée

    // Préparation et exécution de la requête pour mettre à jour la colonne "accepter" à 0
    $sql_update_accepter = "UPDATE demandes_conge SET accepter = 0 WHERE id_dem_conge = ?";
    $stmt_update_accepter = mysqli_prepare($conn, $sql_update_accepter);
    mysqli_stmt_bind_param($stmt_update_accepter, "i", $id_nouvelle_demande);
    mysqli_stmt_execute($stmt_update_accepter);

    echo "<script>alert('Demande envoyer avec succès.'); window.location.href = '/Resident/resident.php';</script>";

} else {
    echo "<script>alert('Erreur lors de l'envoie de la demande: " . mysqli_error($conn) . "'); window.location.href = '/Resident/Congée_resident.php';</script>";
}

// Fermeture de la connexion
mysqli_close($conn);
?>
