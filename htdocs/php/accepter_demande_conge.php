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
$sql = "";
// Vérification de la méthode de la requête et de la présence de l'ID de la demande de congé
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id_dem_conge"])) {
    // éviter les injections SQL
    $id_dem_conge = mysqli_real_escape_string($conn, $_POST["id_dem_conge"]);

    // Requête pour mettre à jour le statut "accepter" dans la base de données
    $sql = "UPDATE demandes_conge SET accepter = '1' WHERE id_dem_conge = '$id_dem_conge'";

    if (mysqli_query($conn, $sql)) {
        
        echo json_encode(array("success" => true));
        header("Refresh:0");
    } else {
        
        echo json_encode(array("success" => false));
    }
} else {
    
    echo json_encode(array("success" => false, "error" => "Requête invalide"));
}
var_dump($_SERVER["REQUEST_METHOD"]);
var_dump(isset($_POST["id_dem_conge"]));

error_log("SQL query: " . $sql);
error_log("SQL error: " . mysqli_error($conn));

// Fermeture de la connexion
mysqli_close($conn);
?>
