<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

$con = mysqli_connect("localhost", "root", "root", "teste");
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

$username = mysqli_real_escape_string($con, $_POST['username_or_email']);

$sql = "SELECT * FROM members WHERE username = '$username'";
$result = $con->query($sql);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $_SESSION['username'] = $row['username'];
    if ($username === 'root') { // Vérifie si le nom d'utilisateur est "root"
        header("Location: brut_res_conection.php"); 
    } else {
        header("Location: /resident/resident.php"); // Redirige vers la page normale
    }
    
    exit();
} else {
    echo "User not found!";
}

$con->close();
?>
