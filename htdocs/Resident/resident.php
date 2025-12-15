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
        .sidebar header .image img{
    width: 80px;
    border-radius: 6px;
}
.sidebar header .image img{
    width: 80px;
    border-radius: 6px;
}

.content {
        font-size: 250%;
    font-weight: 500;
    color: black;
    padding: 12px 60px;
    }

    .circle-container {
    display: flex;
    justify-content: space-around;
    align-items: center;
}
.circle-wrapper {
    position: relative;
    text-align: center;
    font-size: 24px;
    font-weight: bold;
    
}
    .circle {
    width: 200px;
    height: 200px;
    border-radius: 50%;
    background-color: rgba(255, 99, 132, 0.6);
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 24px;
    font-weight: bold;
    color: #fff;
    margin: 10px;
}
.colors{
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
         <div class="centered" >
           
            <h1 class="colors">Tableau de Bord</h1>
            
            <div class="colors">
             <center><h2 >Activité Pratique Validé</h2></center>
             <div class="circle-container">
                    <div class="circle-wrapper">
                        <h2>Acte Maitrise</h2>
                        <div class="circle" id="ActeMaitriseCount">Loading...</div>
                    </div>
                    <div class="circle-wrapper">
                        <h2>Operateur Aide</h2>
                        <div class="circle" id="OperateuraideCount">Loading...</div>
                    </div>
                    <div class="circle-wrapper">
                        <h2>Aide Operateur</h2>
                        <div class="circle" id="AideOperateurCount">Loading...</div>
                    </div>
                    <div class="circle-wrapper">
                        <h2>Observateur</h2>
                        <div class="circle" id="ObservateurCount">Loading...</div>
                    </div>
                </div>
            </div>
          </div>  
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