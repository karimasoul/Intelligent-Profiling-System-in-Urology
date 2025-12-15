<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

$con = mysqli_connect("localhost", "root", "root", "teste");
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

$username = mysqli_real_escape_string($con, $_POST['username_or_email']);
$password = mysqli_real_escape_string($con, $_POST['password']);

$sql = "SELECT * FROM members WHERE username = '$username'";
$result = $con->query($sql);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if (password_verify($password, $row['password'])) {
        $_SESSION['username'] = $row['username'];
        if ($username === 'root') { // Vérifie si le nom d'utilisateur est "root"
            header("Location: /Chef_service/chef_service.php"); // Redirige vers une page spécifique pour "root"
        } else {
            header("Location: /resident/resident.php"); // Redirige vers la page normale
        }
        exit();
    } else {
        header("Location: http://localhost:8083/");
    }
} else {
    header("Location: http://localhost:8083/");
}

$con->close();
?>