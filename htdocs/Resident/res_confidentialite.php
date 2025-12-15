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

// Récupération de la valeur de notification pour l'utilisateur de session
$username = $_SESSION['username'];
$sql = "SELECT notification FROM notif WHERE username = '$username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // L'utilisateur a des notifications
    $row = $result->fetch_assoc();
    $notification = $row["notification"];}

//username 
$sql = "SELECT p.nom_personnel 
FROM personnels AS p 
JOIN members AS m ON m.id_personnel = p.id_personnel
WHERE m.username = '$username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // L'utilisateur a des notifications
    $row = $result->fetch_assoc();
    $nom = $row["nom_personnel"];}

?>

<!DOCTYPE html>

  
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="/css/style_interface.css">
    <title>CARES 3.0</title>
    <style>
   .content {
    font-size: 250% !important;
    font-weight: 500;
    color: black;
    padding: 12px 60px;
}



form .separation {
    width: 100%;
    height: 3px; 
    background-color: #1404c0;
}

form .corps-formulaire {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    grid-gap: 20px;
}

form .corps-formulaire .groupe {
    position: relative;
    display: flex;
    flex-direction: column;
}

form .corps-formulaire .groupe label {
    margin-bottom: 5px;
    width: 200px; 
    display: inline-block; 
    white-space: nowrap; 
}

form .corps-formulaire .groupe input,
form .corps-formulaire .groupe textarea {
    padding: 10px;
    border: 3px solid #1404c0;
    border-radius: 5px;
    outline: none;
}

form .corps-formulaire .groupe input[type="file"] {
    border: none;
}

form .corps-formulaire .groupe input[type="file"]:focus {
    outline: 3px solid #1404c0;
}

form .pied-formulaire input[type="submit"] {
    margin-top: 10px;
    background-color: #1404c0;
    color: white;
    font-size: 15px;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    outline: none;
    cursor: pointer;
    transition: transform 0.5s;
}

form .pied-formulaire input[type="submit"]:hover {
    transform: scale(1.05);
}


@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}

:root {
    /* ===== Colors ===== */
    --body-color: #E4E9F7;
    --sidebar-color: #FFF;
    --primary-color: #1404c0;
    --primary-color-light: #F6F5FF;
    --toggle-color: #DDD;
    --text-color: black;

    /* ====== Transition ====== */
    --tran-03: all 0.2s ease;
    --tran-03: all 0.3s ease;
    --tran-04: all 0.3s ease;
    --tran-05: all 0.3s ease;
}

body {
    min-height: 100vh;
    background-color: var(--body-color);
    transition: var(--tran-05);
}

::selection {
    background-color: var(--primary-color);
    color: #fff;
}

body.dark {
    --body-color: #18191a;
    --sidebar-color: #242526;
    --primary-color: #3a3b3c;
    --primary-color-light: #3a3b3c;
    --toggle-color: #fff;
    --text-color: #ccc;
}


.sidebar {
    position: fixed;
    top: 0;
    left: 0;
    height: 100%;
    width: 250px;
    padding: 10px 14px;
    background: var(--sidebar-color);
    transition: var(--tran-05);
    z-index: 100;
}

.sidebar.close {
    width: 88px;
}

.sidebar li {
    height: 50px;
    list-style: none;
    display: flex;
    align-items: center;
    margin-top: 10px;
}

.sidebar header .image,
.sidebar .icon {
    min-width: 60px;
    border-radius: 6px;
}

.sidebar .icon {
    min-width: 60px;
    border-radius: 6px;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.sidebar .text,
.sidebar .icon {
    color: var(--text-color);
    transition: var(--tran-03);
}

.sidebar .text {
    font-size: 17px;
    font-weight: 500;
    white-space: nowrap;
    opacity: 1;
}

.sidebar.close .text {
    opacity: 0;
}

.sidebar header {
    position: relative;
}

.sidebar header .image-text {
    margin-top: 20px;
    display: flex;
    align-items: center;
}

.sidebar header .logo-text {
    display: flex;
    flex-direction: column;
}

header .image-text .name {
    margin-top: 2px;
    font-size: 18px;
    font-weight: 600;
}

.sidebar header .image {
    display: flex;
    align-items: center;
    justify-content: center;
}

.sidebar header .image img {
    width: 80px;
    border-radius: 6px;
}

.sidebar header .toggle {
    position: absolute;
    top: 50%;
    right: -25px;
    transform: translateY(-50%) rotate(180deg);
    height: 25px;
    width: 25px;
    background-color: var(--primary-color);
    color: var(--sidebar-color);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    cursor: pointer;
    transition: var(--tran-05);
}

body.dark .sidebar header .toggle {
    color: var(--text-color);
}

.sidebar.close .toggle {
    transform: translateY(-50%) rotate(0deg);
}

.sidebar .menu {
    margin-top: 40px;
}



.sidebar li a {
    list-style: none;
    height: 100%;
    background-color: transparent;
    display: flex;
    align-items: center;
    height: 100%;
    width: 100%;
    border-radius: 6px;
    text-decoration: none;
    transition: var(--tran-03);
}

.sidebar li a:hover {
    background-color: var(--primary-color);
}

.sidebar li a:hover .icon,
.sidebar li a:hover .text {
    color: var(--sidebar-color);
}

body.dark .sidebar li a:hover .icon,
body.dark .sidebar li a:hover .text {
    color: var(--text-color);
}

.sidebar .menu-bar {
    height: calc(100% - 55px);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    overflow-y: scroll;
}

.menu-bar::-webkit-scrollbar {
    display: none;
}

.sidebar .menu-bar .mode {
    border-radius: 6px;
    background-color: var(--primary-color-light);
    position: relative;
    transition: var(--tran-05);
}

.menu-bar .mode .sun-moon {
    height: 50px;
    width: 60px;
}

.mode .sun-moon i {
    position: absolute;
}

.mode .sun-moon i.sun {
    opacity: 0;
}

body.dark .mode .sun-moon i.sun {
    opacity: 1;
}

body.dark .mode .sun-moon i.moon {
    opacity: 0;
}

.menu-bar .bottom-content .toggle-switch {
    position: absolute;
    right: 0;
    height: 100%;
    min-width: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    cursor: pointer;
}

.toggle-switch .switch {
    position: relative;
    height: 22px;
    width: 40px;
    border-radius: 25px;
    background-color: var(--toggle-color);
    transition: var(--tran-05);
}

.switch::before {
    content: '';
    position: absolute;
    height: 15px;
    width: 15px;
    border-radius: 50%;
    top: 50%;
    left: 5px;
    transform: translateY(-50%);
    background-color: var(--sidebar-color);
    transition: var(--tran-04);
}

body.dark .switch::before {
    left: 20px;
}

.home {
    position: absolute;
    top: 0;
    left: 250px;
    height: 100vh;
    width: calc(100% - 250px);
    background-color: var(--body-color);
    transition: var(--tran-05);
}

.home .text {
    font-size: 30px !important;
    font-weight: 500;
    color: var(--text-color);
    padding: 12px 60px;
}

.sidebar.close ~ .home {
    left: 78px;
    height: 100vh;
    width: calc(100% - 78px);
}

body.dark .home .text {
    color: var(--text-color);
}

.colors {
    color: var(--text-color);
}

    </style>

</head>
<body>
    <nav class="sidebar close">
        <header>
            <div class="image-text">
                <span class="image">
                    <img src="/css/pictures/logo/cares.png" >
                    
                </span>

                <div class="text logo-text">
                    <span class="name">CARES</span>
                    
                </div>
            </div>

            <i class='bx bx-chevron-right toggle'></i>
        </header>

        <div class="menu-bar">
            <div class="menu">

               

                <ul class="menu-links">
                    <li class="nav-link">
                        <a href="resident.php">
                            <i class='bx bx-home-alt icon' ></i>
                            <span class="text nav-text">Tableau de bord</span>
                        </a>
                    </li>

                    <li class="nav-link">
    <a href="nouvelle_activité.php">
        <?php if($notification == 1): ?>
            <i class='bx bx-bell icon' style="background-color: red;"></i>
        <?php else: ?>
            <i class='bx bx-bar-chart-alt-2 icon'></i>
        <?php endif; ?>
        <span class="text nav-text">Nouvelle activité</span>
    </a>
</li>


                    <li class="nav-link">
                        <a href="Congée_resident.php">
                            <i class='bx bx-pie-chart-alt icon' ></i>
                            <span class="text nav-text">Demande de congée</span>
                        </a>
                    </li>

                    <li class="nav-link">
                        <a href="res_confidentialite.php">
                            <i class='bx bx-wallet icon' ></i>
                            <span class="text nav-text">Confidentialité</span>
                        </a>
                    </li>

                </ul>
            </div>

            <div class="bottom-content">
                <li class="">
                    <a href="/php/logout.php">
                        <i class='bx bx-log-out icon' ></i>
                        <span class="text nav-text">Logout</span>
                    </a>
                </li>

                <li class="mode">
                    <div class="sun-moon">
                        <i class='bx bx-moon icon moon'></i>
                        <i class='bx bx-sun icon sun'></i>
                    </div>
                    <span class="mode-text text">Dark mode</span>

                    <div class="toggle-switch">
                        <span class="switch"></span>
                    </div>
                </li>
                
            </div>
        </div>

    </nav>

    <section class="home">
    <div class="text">Interface Resident : <?php echo $nom; ?></div>
       
        <br>
        <br>
        <br>
        <div class="content">
         <div class="centered">
           
         <h1 class="text">Modifier Mot de passe</h1>
         <!-- Formulaire -->
         <form id="changePasswordForm" class="colors" action="/php/changer_mdp.php" method="post">
    <div class="separation"></div>
    <br>
    <div class="corps-formulaire">
        <div class="groupe">
            <label>Mot de passe actuel :</label>
            <input type="password" name="oldPassword" required>
            <i class="fas fa-lock"></i>
        </div>
        <br>
        <div class="groupe">
            <label>Nouveau mot de passe :</label>
            <input type="password" name="newPassword" required>
            <i class="fas fa-lock"></i>
        </div>
    </div>
    <br>
    <div class="pied-formulaire" align="center">
        <input type="submit" value="Changer le mot de passe">
    </div>
</form>
            
            
            </div>
          </div>  
          <script>

            // valider le formulaire avant soumission
document.getElementById('changePasswordForm').onsubmit = function() {
    var oldPassword = document.getElementsByName('oldPassword')[0].value;
    var newPassword = document.getElementsByName('newPassword')[0].value;
    
    // Vérifier que les champs ne sont pas vides
    if (oldPassword.trim() === '' || newPassword.trim() === '') {
        alert('Veuillez remplir tous les champs.');
        return false; // Empêcher la soumission du formulaire
    }

    
    
    return true; // Permettre la soumission du formulaire
};

          </script>
        <script>
        // Effectuer une requête AJAX pour récupérer les données
        fetch('/php/res_statistic.php')
    .then(response => response.json())
    .then(data => {
        const acteMaitriseCount = data.ActeMaitrise.length;
        const operateurAideCount = data.Operateuraide.length;
        const aideOperateurCount = data.AideOperateur.length;
        const ObservateurCount = data.Observateur.length;

        // Mettre à jour le contenu des cercles avec les données récupérées
        const acteMaitriseCircle = document.getElementById('ActeMaitriseCount');
        acteMaitriseCircle.textContent = acteMaitriseCount;
        

        const operateurAideCircle = document.getElementById('OperateuraideCount');
        operateurAideCircle.textContent = operateurAideCount;

        const aideOperateurCircle = document.getElementById('AideOperateurCount');
        aideOperateurCircle.textContent = aideOperateurCount;

        const ObservateurCircle = document.getElementById('ObservateurCount');
        ObservateurCircle.textContent = ObservateurCount;
    })
    .catch(error => {
        console.error('Erreur lors de la récupération des données:', error);
    });
        </script>
        
    </section>

    <script>
        const body = document.querySelector('body'),
      sidebar = body.querySelector('nav'),
      toggle = body.querySelector(".toggle"),
      
      modeSwitch = body.querySelector(".toggle-switch"),
      modeText = body.querySelector(".mode-text");


toggle.addEventListener("click" , () =>{
    sidebar.classList.toggle("close");
})



modeSwitch.addEventListener("click" , () =>{
    body.classList.toggle("dark");
    
    if(body.classList.contains("dark")){
        modeText.innerText = "Light mode";
    }else{
        modeText.innerText = "Dark mode";
        
    }
});
    </script>

</body>
</html>