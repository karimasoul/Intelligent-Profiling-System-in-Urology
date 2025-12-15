<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: http://localhost:8083/"); 
    exit();
}
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
                        <a href="chef_service.php">
                            <i class='bx bx-home-alt icon' ></i>
                            <span class="text nav-text">Tableau de bord</span>
                        </a>
                    </li>

                    <li class="nav-link">
                        <a href="validation_act_pratique.php">
                            <i class='bx bx-bar-chart-alt-2 icon'></i>
                            <span class="text nav-text">Gestion des activités</span>
                        </a>
                    </li>
                    
                    <li class="nav-link">
                      <a href="congée.php">
                          <i class='bx bx-pie-chart-alt icon'></i>
                          <span class="text nav-text">Gestion des congées</span>
                      </a>
                  </li>
                    
                  <li class="nav-link">
                    <a href="Proc_profiling.php">
                        <i class='bx bx-user icon' ></i>
                        <span class="text nav-text">Processus de Profiling</span>
                    </a>
                </li>

                <li class="nav-link ">
                    <a href="imprimer_acte_pratique.php">
                    <i class='bx bx-printer icon' ></i>
                        <span class="text nav-text">Planification</span>
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
     
        <div class="text">CARES 3.0 - Interface du Chef de service</div>
        <br>
        <br>
        <br>
        <div class="content">
         <div class="centered">
           
             <center><h2 class="text">Validation des activité pratique</h2></center>

             <table id="data-table" class="tableau-validation">
            <thead>
                <tr>
                    <th>Résident</th>
                    <th>Catégorie</th>
                    <th>Organe</th>
                    <th>Acte princiaple</th>
                    <th>Acte secondaire</th>
                    <th>Role Résident</th>
                    <th>Statut d'acceptation</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </section>

    
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const tbody = document.querySelector("#data-table tbody");

        // Fonction pour afficher le message de confirmation
        function showConfirmationMessage(id) {
            const confirmation = confirm("Êtes-vous sûr de vouloir valider l'activité pratique ?");
            if (confirmation) {
                // Requête AJAX pour valider l'activité pratique 
                const xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4) {
                        if (this.status == 200) {
                            alert(this.responseText); // Afficher le message de succès ou d'erreur
                            fetchData(); // Rafraîchir les données 
                            window.location.href = "validation_act_pratique.php";
                        } else {
                            alert("Une erreur s'est produite lors de la communication avec le serveur.");
                        }
                    }
                };
                xhttp.open("GET", "/php/update_medecin_valid.php?id_relation=" + id, true);
                xhttp.send();
            }
        }

        
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
                        var keys = ['nom_personnel', 'nom_cat', 'nom_org', 'nom_actePrincipal', 'nom_acteSecondaire', 'role_resid'];
                        keys.forEach(function(key) {
                            var cell = document.createElement("td");
                            cell.textContent = item[key];
                            row.appendChild(cell);
                        });

                        // Créer la cellule pour le bouton d'acceptation
                        var accepterCell = document.createElement("td");
                        var accepterButton = document.createElement("button");
                        accepterButton.classList.add("accepter-button");
                        accepterButton.textContent = "Accepter";
                        accepterButton.addEventListener("click", function() {
                            showConfirmationMessage(item.id_rel);
                        });

                        // Changer la classe du bouton en fonction de la valeur de medecin_valid
                        if (item.medecin_valid === null) {
                            accepterButton.classList.add("refuser");
                        } else {
                            accepterButton.classList.add("accepter");
                            accepterButton.disabled = true; // Désactiver le bouton si le congé est déjà validé
                        }

                        accepterCell.appendChild(accepterButton);
                        row.appendChild(accepterCell);

                        tbody.appendChild(row);
                    });
                }
            };
            xhttp.open("GET", "/php/data_acte_pratique.php", true);
            xhttp.send();
        }

        fetchData(); 
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