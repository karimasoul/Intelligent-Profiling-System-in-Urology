<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
// Récupérer les données envoyées depuis le formulaire
$actePrincipale = $_POST['actePrincipale'];
$acteSecondaire = $_POST['acteSecondaire'];
$org = $_POST['org'];
$cat = $_POST['cat'];
$date_debut = $_POST['date_debut'];
$date_fin = $_POST['date_fin'];

$operateurAide = $_POST['operateurAide'];
$acteMaitrise = $_POST['acteMaitrise'];
$aideOperateur = $_POST['aideOperateur'];
$observateur = $_POST['observateur'];

//////////////

$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "teste";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}
/////////////////////////////////////////////////////
// Requête SQL pour récupérer l'ID de l'opérateur d'aide
$sql_operateur_aide = "SELECT id FROM members WHERE id_personnel = ?";
$stmt_operateur_aide = $conn->prepare($sql_operateur_aide);
$stmt_operateur_aide->bind_param("i", $operateurAide);
$stmt_operateur_aide->execute();
$result_operateur_aide = $stmt_operateur_aide->get_result();
$operateur_aide_row = $result_operateur_aide->fetch_assoc();
$operateurAide = $operateur_aide_row['id'];

// Requête SQL pour récupérer l'ID de l'acte de maîtrise
$sql_acte_maitrise = "SELECT id FROM members WHERE id_personnel = ?";
$stmt_acte_maitrise = $conn->prepare($sql_acte_maitrise);
$stmt_acte_maitrise->bind_param("i", $acteMaitrise);
$stmt_acte_maitrise->execute();
$result_acte_maitrise = $stmt_acte_maitrise->get_result();
$acte_maitrise_row = $result_acte_maitrise->fetch_assoc();
$acteMaitrise = $acte_maitrise_row['id'];

// Requête SQL pour récupérer l'ID de l'aide opérateur
$sql_aide_operateur = "SELECT id FROM members WHERE id_personnel = ?";
$stmt_aide_operateur = $conn->prepare($sql_aide_operateur);
$stmt_aide_operateur->bind_param("i", $aideOperateur);
$stmt_aide_operateur->execute();
$result_aide_operateur = $stmt_aide_operateur->get_result();
$aide_operateur_row = $result_aide_operateur->fetch_assoc();
$aideOperateur = $aide_operateur_row['id'];

// Requête SQL pour récupérer l'ID de l'observateur
$sql_observateur = "SELECT id FROM members WHERE id_personnel = ?";
$stmt_observateur = $conn->prepare($sql_observateur);
$stmt_observateur->bind_param("i", $observateur);
$stmt_observateur->execute();
$result_observateur = $stmt_observateur->get_result();
$observateur_row = $result_observateur->fetch_assoc();
$observateur = $observateur_row['id'];

//////////////////////////////////////////////////////////


// Préparer la requête pour obtenir les ids
$sql = "SELECT id_apap FROM activite_pratique_acteprincipal WHERE nom_actePrincipal= '$actePrincipale'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$id_apap = $row['id_apap'];

$sql = "SELECT id_apas FROM activite_pratique_actesecondaire WHERE nom_acteSecondaire= '$acteSecondaire'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$id_apas = $row['id_apas'];

$sql = "SELECT id_apc FROM activite_pratique_categorie WHERE nom_cat= '$cat'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$id_apc = $row['id_apc'];

$sql = "SELECT id_aporg FROM activite_pratique_organe WHERE nom_org= '$org'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$id_aporg = $row['id_aporg'];

///////////////////////////////////

$sql_get_last_id = "SELECT MAX(id_rel_act_resid) AS max_id FROM relation_actp_resid_med";
$result = $conn->query($sql_get_last_id);
$row = $result->fetch_assoc();
$last_id = $row['max_id'] ?? 0; // Si aucun résultat, on commence à 0

// Préparation de la requête d'insertion
$sql_insert = "INSERT INTO relation_actp_resid_med (
    id_resid, id_apc, id_aporg, id_apap, id_apas, role_resid, validation, medecin_valid, description, id_rel_act_resid, date_debut
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql_insert);

// Vérifiez que la préparation a réussi
if ($stmt === false) {
    die("Échec de la préparation de la requête : " . $conn->error);
}

// 
$validation = 1;
$medecin_valid = NULL;
$description = "";

// Insertion pour chaque rôle de résident
$roles = [
    'Operateur aide' => $operateurAide,
    'Acte Maitrise' => $acteMaitrise,
    'Aide Operateur' => $aideOperateur,
    'Observateur' => $observateur
];

$insertion_success = true; // Flag pour verifier success de l'insertion 

foreach ($roles as $role => $id_resid) {
    if (!empty($id_resid)) {
        $last_id++; // Incrémenter l'ID pour chaque nouvelle insertion
        $stmt->bind_param("iiiiisiiiss", $id_resid, $id_apc, $id_aporg, $id_apap, $id_apas, $role, $validation, $medecin_valid, $description, $last_id, $date_debut);
        if (!$stmt->execute()) {
            $insertion_success = false; 
            break;
        }
        //notification
        $sql_update = "UPDATE notif 
            INNER JOIN members ON notif.username = members.username 
            SET notif.notification = 1 
            WHERE members.id = '$id_resid'";
        if (mysqli_query($conn, $sql_update)) {
            echo "La valeur de notification a été mise à jour avec succès.";
        } else {
            echo "Erreur lors de la mise à jour de la valeur de notification : " . mysqli_error($conn);
        }
        //fin notification
    }
}

// Fermer la déclaration et la connexion
$stmt->close();
$conn->close();

// redirection et montrer le message pop
if ($insertion_success) {
    echo "<script>
            alert('Opération effectuée avec succès');
            window.location.href = '/Chef_service/chef_service.php';
          </script>";
    exit;
} else {
    echo "<script>
            alert('Une erreur s\'est produite lors de l\'insertion');
            window.location.href = '/Chef_service/Proc_profiling.php';
          </script>";
    exit;
}
} else {
// Rediriger vers la page principale si le formulaire n'a pas été soumis
header("Location: /Chef_service/Proc_profiling.php");
exit;
}
?>