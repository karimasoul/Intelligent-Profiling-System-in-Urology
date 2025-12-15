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
    h1 {
        color: var(--text-color);
    }
        .sidebar header .image img{
    width: 80px;
    border-radius: 6px;
}
        .accepter {
    background-color: green;
    color: white;
}

.refuser {
    background-color: red;
    color: white;
}

        body {
    font-family: Arial, sans-serif;
    background-color: #f0f0f0;
}

.tableau-conges {
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

                <li class="nav-link">
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
        <center><h1>Les demandes de congés</h1></center>
        <table id="data-table" class="tableau-conges">
            <thead>
                <tr>
                    <th>Résident</th>
                    <th>Date de début</th>
                    <th>Date de fin</th>
                    <th>Justificatif</th>
                    <th></th>
                    <th>Statut d'acceptation</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>

        <script>
        document.addEventListener("DOMContentLoaded", function() {
            const tbody = document.querySelector("#data-table tbody");

            // Fonction pour afficher le message de confirmation et accepter la demande de congé
            function showConfirmationMessage(id) {
                const confirmation = confirm("Êtes-vous sûr de vouloir accepter cette demande de congé ?");
                if (confirmation) {
                    const xhttp = new XMLHttpRequest();
                    xhttp.onreadystatechange = function() {
                        if (this.readyState == 4) {
                            if (this.status == 200) {
                                const response = JSON.parse(this.responseText);
                                if (response.success) {
                                    // Mettre à jour l'état du bouton et du statut dans le tableau
                                    const acceptButton = document.querySelector(`#accept-button-${id}`);
                                    acceptButton.disabled = true;
                                    acceptButton.textContent = "Accepté";
                                } else {
                                    alert("Une erreur s'est produite lors de l'acceptation de la demande de congé.");
                                }
                            } else {
                                alert("Une erreur s'est produite lors de la communication avec le serveur.");
                            }
                        }
                    };
                    xhttp.open("POST", "/php/accepter_demande_conge.php", true);
                    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                    xhttp.send("id_dem_conge=" + encodeURIComponent(id));
                    // Recharger la page après l'acceptation de la demande de congé
                    location.reload();
                }
            }

            // Fonction pour récupérer les données et les afficher dans le tableau
            function fetchData() {
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        var data = JSON.parse(this.responseText);
                        data.forEach(function(item) {
                            var row = document.createElement("tr");

                            var idCell = document.createElement("td");
                            idCell.textContent = item.nom_personnel;
                            row.appendChild(idCell);

                            var dateDebutCell = document.createElement("td");
                            dateDebutCell.textContent = item.date_debut;
                            row.appendChild(dateDebutCell);

                            var dateFinCell = document.createElement("td");
                            dateFinCell.textContent = item.date_fin;
                            row.appendChild(dateFinCell);

                            var justificatifCell = document.createElement("td");
                            justificatifCell.textContent = item.justificatif;
                            row.appendChild(justificatifCell);

                            var fichierCell = document.createElement("td");
                            fichierCell.textContent = item.fichier;
                            row.appendChild(fichierCell);

                            var accepterCell = document.createElement("td");
                            var accepterButton = document.createElement("button");
                            accepterButton.classList.add("accepter-button");
                            var accepterClass = item.accepter === '1' ? 'accepter' : 'refuser';
                            accepterButton.classList.add(accepterClass);
                            accepterButton.textContent = "Accepter";
                            accepterButton.addEventListener("click", function() {
                                if (item.accepter !== "1") {
                                    showConfirmationMessage(item.id_dem_conge);
                                }
                            });
                            accepterCell.appendChild(accepterButton);
                            row.appendChild(accepterCell);

                            tbody.appendChild(row);
                        });
                    }
                };
                xhttp.open("GET", "/php/data_conge.php", true);
                xhttp.send();
            }

            fetchData(); // Appele de la fonction
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
