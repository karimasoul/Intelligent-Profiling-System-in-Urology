<?php

if (isset($_GET['file'])) {
    // Récupérer le nom du fichier CSV
    $csv_file = $_GET['file'];
    
    // Vérifier si le fichier existe
    if (file_exists($csv_file)) {
        // Définir les en-têtes pour le téléchargement du fichier CSV
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename="' . basename($csv_file) . '"');
        
        // Lire le contenu du fichier CSV et l'envoyer au navigateur
        readfile($csv_file);
        
        
        exit;
    } else {
        // Afficher un message d'erreur si le fichier n'existe pas
        echo "Erreur : Le fichier CSV spécifié n'existe pas.";
    }
} else {
    // Rediriger vers une page d'erreur si le fichier n'est pas défini
    header("Location: erreur.php");
    exit;
}
?>
