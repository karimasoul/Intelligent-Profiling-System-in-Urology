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

// Récupérer les données de la table où medecin_valid est NULL
$query = "SELECT d.id_resid,p.nom_personnel,d.id_dem_conge, d.date_debut, d.date_fin,d.justificatif, d.accepter 
FROM demandes_conge AS d 
LEFT JOIN 
members AS m ON d.id_resid = m.id
LEFT JOIN 
personnels AS p ON m.id_personnel = p.id_personnel ";
$result = mysqli_query($conn, $query);

$data = [];
if (mysqli_num_rows($result) > 0) {
    // Ajouter les données dans un tableau
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
}

// Retourner les données sous forme de JSON
header('Content-Type: application/json');
echo json_encode($data);
?>
