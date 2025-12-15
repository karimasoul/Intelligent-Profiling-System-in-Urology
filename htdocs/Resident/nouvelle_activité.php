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

    $sql_update = "UPDATE notif SET notification = 0 WHERE username = '$username'";
    if (mysqli_query($conn, $sql_update)) {
        echo "La valeur de notification a été mise à jour avec succès.";
    } else {
        echo "Erreur lors de la mise à jour de la valeur de notification : " . mysqli_error($conn);
    }


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
        
        .accepter-button {
            padding: 5px 10px;
            border: none;
            cursor: pointer;
        }

        .accepter {
            background-color: green;
            color: white;
        }

        .refuser {
            background-color: red;
            color: white;
        }

        .tableau-validation {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
    padding-left: 200px;
    margin-left: 20px;
    margin-right: 200px;
}

th, td {
    padding: 10px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

thead {
    background-color: #f2f2f2;
}

th {
    background-color: #4CAF50;
    color: white;
}

tr:nth-child(even) {
    background-color: #f2f2f2;
}

tr:hover {
    background-color: #ddd;
}
.sidebar header .image img{
    width: 80px;
    border-radius: 6px;
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
         <div class="centered">
           
             <center><h2 class="text">Activité pratique non validé</h2></center>

             <table id="data-table" class="tableau-validation">
            <thead>
                <tr>
                    <th>catégorie</th>
                    <th>organe</th>
                    <th>Activité princiaple</th>
                    <th>Activité secondaire</th>
                    <th>Role Résident</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>


        <script>
             document.addEventListener("DOMContentLoaded", function() {
    const tbody = document.querySelector("#data-table tbody");

    // Fonction pour récupérer les données et les afficher dans le tableau
    function fetchData() {
        // Requête AJAX pour récupérer les données du fichier PHP
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var data = JSON.parse(this.responseText);
                data.reverse();
                data.forEach(function(item) {
                    var row = document.createElement("tr");

                    // Créer les cellules de la ligne avec les données
                    var keys = ['nom_cat', 'nom_org', 'nom_actePrincipal', 'nom_acteSecondaire', 'role_resid'];
                    keys.forEach(function(key) {
                        var cell = document.createElement("td");

                        // Si la clé est 'nom_cat' ou 'nom_aporg', afficher dans la colonne correspondante
                        if (key === 'nom_cat' || key === 'nom_aporg') {
                            cell.textContent = item[key];
                        } else {
                            // Sinon, afficher dans les colonnes restantes
                            cell.textContent = item[key];
                        }

                        row.appendChild(cell);
                    });

                    tbody.appendChild(row);
                });
            }
        };
        xhttp.open("GET", "/php/res_data_acte_pratique.php", true);
        xhttp.send();
    }

    fetchData(); // Appeler la fonction pour récupérer et afficher les données
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