<?php
session_start();

$con = mysqli_connect("localhost", "root", "root", "teste");
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Vérifiez si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Préparez et exécutez la requête d'insertion pour le nouveau compte
    $stmt_insert = $con->prepare("INSERT INTO members (username, password, salt, type_compte, id_personnel) VALUES (?, ?, ?, 1, ?)");
    
    // Vérifiez si la préparation de la requête a réussi
    if ($stmt_insert === false) {
        die("Erreur de préparation de la requête d'insertion: " . $con->error);
    }

    // Récupérez les données du formulaire
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hash du mot de passe
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Générez un sel pour le champ 'salt' 
    $salt = uniqid();

    // Préparez et exécutez la requête d'insertion pour la table 'personnels'
    $stmt_insert_personnels = $con->prepare("INSERT INTO personnels (nom_personnel, prenom_personnel, date_naissance_personnel, lieu_naissance_personnel, adresse_personnel, mail_personnel, tel_personnel, id_grade, annee_recreutement) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

    // Vérifiez si la préparation de la requête a réussi
    if ($stmt_insert_personnels === false) {
        die("Erreur de préparation de la requête d'insertion dans la table 'personnels': " . $con->error);
    }

    // donnee par défaut
    $date_naissance_par_defaut = '1998-02-02';
    $lieu_naissance_personnel = 'oran';
    $adresse_par_defaut = 'adresse par défaut'; 
    $mail_personnel = 'adresse@adresse.com';
    $tel_personnel = '06666666666';
    $id_grade = '3';
    $annee_recreutement = '2024';

    // Liez les paramètres et exécutez la requête d'insertion pour la table 'personnels'
    $stmt_insert_personnels->bind_param("ssssssssi", $username, $username, $date_naissance_par_defaut, $lieu_naissance_personnel, $adresse_par_defaut, $mail_personnel, $tel_personnel, $id_grade, $annee_recreutement);

    // Exécutez la requête d'insertion préparée pour la table 'personnels'
    if ($stmt_insert_personnels->execute()) {
        // Récupérez l'ID inséré pour le nouveau personnel
        $id_personnel = $stmt_insert_personnels->insert_id;
        // Fermez la requête d'insertion pour la table 'personnels'
        $stmt_insert_personnels->close();

        // Préparez et exécutez la requête d'insertion pour la table 'annee_res_univ'
        $stmt_insert_annee_res_univ = $con->prepare("INSERT INTO annee_res_univ (id_resid, id_annee_univ, niveau) VALUES (?, ?, ?)");
        
        // Vérifiez si la préparation de la requête a réussi
        if ($stmt_insert_annee_res_univ === false) {
            die("Erreur de préparation de la requête d'insertion dans la table 'annee_res_univ': " . $con->error);
        }
        
        // ID de l'année universitaire et niveau 
        $id_annee_univ = 9; 
        $niveau = 2; 
        
        // Liez les paramètres et exécutez la requête d'insertion pour la table 'annee_res_univ'
        $stmt_insert_annee_res_univ->bind_param("iii", $id_personnel, $id_annee_univ, $niveau);
        
        // Exécutez la requête d'insertion préparée pour la table 'annee_res_univ'
        if ($stmt_insert_annee_res_univ->execute()) {
           
        } else {
            echo "Erreur lors de l'inscription: " . $con->error;
        }
        
        // Fermez la requête d'insertion pour la table 'annee_res_univ'
        $stmt_insert_annee_res_univ->close();

        // Liez les paramètres et exécutez la requête d'insertion pour la table 'members'
        $stmt_insert->bind_param("sssi", $username, $hashed_password, $salt, $id_personnel);

        // Exécutez la requête d'insertion préparée pour la table 'members'
        
    } else {
        echo "Erreur lors de l'inscription: " . $con->error;
    }
    if ($stmt_insert->execute()) {
        // Ajouter l'utilisateur à la table 'notif' 
        $stmt_insert_notif = $con->prepare("INSERT INTO notif (username, notification) VALUES (?, 0)");
        
        // Vérifiez si la préparation de la requête a réussi
        if ($stmt_insert_notif === false) {
            die("Erreur de préparation de la requête d'insertion dans la table 'notif': " . $con->error);
        }
        
        // Liez les paramètres et exécutez la requête d'insertion pour la table 'notif'
        $stmt_insert_notif->bind_param("s", $username);
        
        // Exécutez la requête d'insertion préparée pour la table 'notif'
        if ($stmt_insert_notif->execute()) {
            header("Location: http://localhost:8083/");
        } else {
            echo "Erreur lors de l'inscription: " . $con->error;
        }
        
        // Fermez la requête d'insertion pour la table 'notif'
        $stmt_insert_notif->close();
    } else {
        echo "Erreur lors de l'inscription: " . $con->error;
    }
}

// Fermez la connexion à la base de données
$con->close();
?>
