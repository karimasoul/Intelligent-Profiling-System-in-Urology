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

// Récupération des données
$sql_ActeMaitrise = "SELECT role_resid FROM relation_actp_resid_med WHERE role_resid='Acte Maitrise' AND medecin_valid IS NOT NULL";
$result_ActeMaitrise = mysqli_query($conn, $sql_ActeMaitrise);


$sql_Operateuraide = "SELECT role_resid FROM relation_actp_resid_med WHERE role_resid='Operateur aide' AND medecin_valid IS NOT NULL";
$result_Operateuraide = mysqli_query($conn, $sql_Operateuraide);


$sql_AideOperateur = "SELECT role_resid FROM relation_actp_resid_med WHERE role_resid='Aide Operateur' AND medecin_valid IS NOT NULL";
$result_AideOperateur = mysqli_query($conn, $sql_AideOperateur);


$sql_Observateur = "SELECT role_resid FROM relation_actp_resid_med WHERE role_resid='Observateur' AND medecin_valid IS NOT NULL";
$result_Observateur = mysqli_query($conn, $sql_Observateur);

// Création de tableaux pour stocker les résultats 
$data_ActeMaitrise = array();
$data_Operateuraide = array();
$data_AideOperateur = array();
$data_Observateur = array();

// Ajout des résultats aux tableau
while($row = mysqli_fetch_assoc($result_ActeMaitrise)) {
    $data_ActeMaitrise[] = $row;
}


while($row = mysqli_fetch_assoc($result_Operateuraide)) {
    $data_Operateuraide[] = $row;
}



while($row = mysqli_fetch_assoc($result_AideOperateur)) {
    $data_AideOperateur[] = $row;
}


while($row = mysqli_fetch_assoc($result_Observateur)) {
    $data_Observateur[] = $row;
}
// Encodage des tableaux au format JSON et envoi de la réponse
$response = array(
    "ActeMaitrise" => $data_ActeMaitrise,
    "Operateuraide" => $data_Operateuraide,
    "AideOperateur" => $data_AideOperateur,
    "Observateur" => $data_Observateur
);

echo json_encode($response);

// Fermeture de la connexion
mysqli_close($conn);
?>
