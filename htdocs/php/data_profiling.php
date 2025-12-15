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

// Récupération des noms des actes principaux
$sql_acte_principal = "SELECT nom_actePrincipal FROM activite_pratique_acteprincipal";
$result_acte_principal = mysqli_query($conn, $sql_acte_principal);

// Récupération des noms des actes secondaires
$sql_acte_secondaire = "SELECT nom_acteSecondaire FROM activite_pratique_actesecondaire";
$result_acte_secondaire = mysqli_query($conn, $sql_acte_secondaire);

// Récupération des noms des org
$sql_acte_org = "SELECT nom_org FROM activite_pratique_organe";
$result_acte_org = mysqli_query($conn, $sql_acte_org);

// Récupération des noms des categories
$sql_acte_cat = "SELECT nom_cat FROM activite_pratique_categorie";
$result_acte_cat = mysqli_query($conn, $sql_acte_cat);

// Création de tableaux pour stocker les résultats 
$data_acte_principal = array();
$data_acte_secondaire = array();
$data_acte_org = array();
$data_acte_cat = array();

// Ajout des résultats des actes principaux au tableau
while($row = mysqli_fetch_assoc($result_acte_principal)) {
    $data_acte_principal[] = $row;
}

// Ajout des résultats des actes secondaires au tableau
while($row = mysqli_fetch_assoc($result_acte_secondaire)) {
    $data_acte_secondaire[] = $row;
}

// Ajout des résultats des actes org au tableau
while($row = mysqli_fetch_assoc($result_acte_org)) {
    $data_acte_org[] = $row;
}

// Ajout des résultats des actes cat au tableau
while($row = mysqli_fetch_assoc($result_acte_cat)) {
    $data_acte_cat[] = $row;
}

// Encodage des tableaux au format JSON et envoi de la réponse
$response = array(
    "acte_principal" => $data_acte_principal,
    "acte_secondaire" => $data_acte_secondaire,
    "acte_org" => $data_acte_org,
    "acte_cat" => $data_acte_cat
);

echo json_encode($response);

// Fermeture de la connexion
mysqli_close($conn);
?>
