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
$query = "SELECT r.id_rel, 
r.id_resid, 
c1.nom_cat, 
c2.nom_org, 
c3.nom_actePrincipal, 
c4.nom_acteSecondaire, 
r.role_resid, 
r.medecin_valid,
p.nom_personnel,
p.prenom_personnel 
FROM relation_actp_resid_med AS r
LEFT JOIN 
activite_pratique_categorie AS c1 ON r.id_apc = c1.id_apc
LEFT JOIN 
activite_pratique_organe AS c2 ON r.id_aporg = c2.id_aporg
LEFT JOIN 
activite_pratique_acteprincipal AS c3 ON r.id_apap = c3.id_apap
LEFT JOIN 
activite_pratique_actesecondaire AS c4 ON r.id_apas = c4.id_apas
LEFT JOIN 
members AS m ON r.id_resid = m.id
LEFT JOIN 
personnels AS p ON m.id_personnel = p.id_personnel 
WHERE medecin_valid IS NULL";
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
