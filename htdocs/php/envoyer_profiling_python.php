<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultat du Calcul</title>
</head>
<body>
    <h2>Résultat du Calcul</h2>
    <?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

    // Récupérer les valeurs envoyée depuis le formulaire HTML
    $actePrincipale = $_POST['actePrincipal'];
    $acteSecondaire = $_POST['acteSecondaire'];
    $org = $_POST['org'];
    $cat = $_POST['cat'];
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];
   

    // Exécuter le script Python 
    $command = "C:\\Users\\ouadk\\AppData\\Local\\Programs\\Python\\Python39\\python.exe C:\\Users\\ouadk\\PycharmProjects\\profiling\\main.py \"$actePrincipale\" \"$acteSecondaire\" \"$org\" \"$cat\" \"$date_debut\" \"$date_fin\"  ";
    $output = shell_exec($command);

    // Afficher le résultat
   
    if ($output !== null) {
        
        //echo "<pre>$output</pre>";
        // Stocker le résultat dans une session
        $_SESSION['actePrincipale'] = $actePrincipale;
        $_SESSION['acteSecondaire'] = $acteSecondaire;
        $_SESSION['org'] = $org;
        $_SESSION['cat'] = $cat;
        $_SESSION['date_debut'] = $date_debut;
        $_SESSION['date_fin'] = $date_fin;
        
        $_SESSION['output'] = $output;
    // Rediriger vers la page de résultat
    header("Location: /Chef_service/resultat_profiling.php");
    exit;
    } else {
        // message d'erreur
        echo "Une erreur s'est produite lors de l'exécution du script Python.";
    }
    ?>
</body>
</html>