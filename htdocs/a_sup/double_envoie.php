<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: http://localhost:8083/"); 
    exit();
}

// Récupérer les données du formulaire
$actePrincipale = $_POST['actePrincipal'];
$acteSecondaire = $_POST['acteSecondaire'];
$org = $_POST['org'];
$cat = $_POST['cat'];
$date_debut = $_POST['date_debut'];
$date_fin = $_POST['date_fin'];


// Envoyer les données au premier fichier PHP
include 'envoyer_profiling_python.php';

// Envoyer les données au deuxième fichier PHP
include 'remplire_acte_pratique_postprofiling.php';
?>
