<?php
session_start();


if (!isset($_SESSION['username'])) {
    echo "<script>alert('Vous n'êtes pas connecté.'); window.location.href = '/Resident/res_confidentialite.php';</script>";
}

// Récupérer les données du formulaire
$oldPassword = $_POST['oldPassword'];
$newPassword = $_POST['newPassword'];
$username_session = $_SESSION['username']; // Renommé pour éviter les conflits de noms de variable


$servername = "localhost";
$username_db = "root"; // Renommé pour éviter les conflits de noms de variable
$password_db = "root"; // Renommé pour éviter les conflits de noms de variable
$dbname = "teste";


$conn = mysqli_connect($servername, $username_db, $password_db, $dbname);


if (!$conn) {
    echo "<script>alert('Échec de la connexion : " . mysqli_connect_error() . "'); window.location.href = '/Resident/res_confidentialite.php';</script>";
}

// Récupérer le mot de passe haché de l'utilisateur depuis la base de données
$sql = "SELECT password FROM members WHERE username='$username_session'"; // Utilisation du nom d'utilisateur de la session
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 1) {
    $row = mysqli_fetch_assoc($result);
    $hashedPassword = $row['password'];
    
    // Vérifier si le mot de passe haché correspond à celui fourni
    if (password_verify($oldPassword, $hashedPassword)) {
        // Générer un hachage sécurisé pour le nouveau mot de passe
        $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        
        // Mettre à jour le mot de passe dans la base de données
        $sql_update = "UPDATE members SET password='$hashedNewPassword' WHERE username='$username_session'"; // Utilisation du nom d'utilisateur de la session
        if (mysqli_query($conn, $sql_update)) {
            echo "<script>alert('Mot de passe changé avec succès.'); window.location.href = '/Resident/resident.php';</script>";
        } else {
            echo "<script>alert('Erreur lors de la mise à jour du mot de passe : " . mysqli_error($conn) . "'); window.location.href = '/Resident/res_confidentialite.php';</script>";
        }
    } else {
        echo "<script>alert('Le mot de passe actuel est incorrect.'); window.location.href = '/Resident/res_confidentialite.php';</script>";
    }
} else {
    echo "<script>alert('Erreur : utilisateur introuvable.'); window.location.href = '/Resident/res_confidentialite.php';</script>";
}

// Fermer la connexion
mysqli_close($conn);
?>
