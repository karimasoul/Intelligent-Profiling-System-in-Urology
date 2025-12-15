<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Vérifier si les données du formulaire ont été soumises
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $actePrincipale = $_POST['actePrincipale'];
    $acteSecondaire = $_POST['acteSecondaire'];
    $org = $_POST['org'];
    $cat = $_POST['cat'];
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];
   
    
    // Récupérer les autres données du formulaire
    $operateurAideId = isset($_POST['operateurAideId']) ? htmlspecialchars($_POST['operateurAideId']) : '';
    $acteMaitriseId = isset($_POST['acteMaitriseId']) ? htmlspecialchars($_POST['acteMaitriseId']) : '';
    $aideOperateurId = isset($_POST['aideOperateurId']) ? htmlspecialchars($_POST['aideOperateurId']) : '';
    $observateurId = isset($_POST['observateurId']) ? htmlspecialchars($_POST['observateurId']) : '';

    // Connexion à la base de données
    $servername = "localhost";
    $username = "root";
    $password = "root";
    $dbname = "teste";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Vérifier la connexion
    if ($conn->connect_error) {
        die("Échec de la connexion : " . $conn->connect_error);
    }

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

    // donne defaut
    $validation = 1;
    $medecin_valid = NULL;
    $description = "";

    // Insertion pour chaque rôle de résident
    $roles = [
        'Operateur aide' => $operateurAideId,
        'Acte Maitrise' => $acteMaitriseId,
        'Aide Operateur' => $aideOperateurId,
        'Observateur' => $observateurId
    ];

    $insertion_success = true; 

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
