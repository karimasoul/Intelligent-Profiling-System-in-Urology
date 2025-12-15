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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    

    <title>CARES 3.0</title>
    <style>
        .sidebar header .image img{
    width: 80px;
    border-radius: 6px;
}
form .pied-formulaire input {
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

.content {
        font-size: 250%;
    font-weight: 500;
    color: var(--text-color);
    padding: 12px 60px;
    }
   
            
            
.select {
  padding: 10px;
  font-size: 16px;
  border: 1px solid #ccc;
  border-radius: 5px;
}


.select option {
  padding: 10px;
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
    <br>
    <br>
    <br>
    <div class="content">
    <center><h2>Formulaire Proc_profiling</h2></center>
     <form action="/php/envoyer_profiling_python.php" method="post"  id="profilingForm"> 
    
        <label for="actePrincipal">Choix de l'acte principal :</label>
        <select name="actePrincipal" id="actePrincipal" class="select" style="width: 300px;">
            
        </select>
        <br>
        <label for="acteSecondaire">Choix de l'acte secondaire :</label>
        <select name="acteSecondaire" id="acteSecondaire" class="select" style="width: 300px;">
            
        </select>
        
        <br>
        <label for="org">Choix de l'organe :</label>
        <select name="org" id="org" class="select" style="width: 300px;">
        </select>
        <br>
        <label for="cat">Choix de la catégorie :</label>
        <select name="cat" id="cat" class="select" style="width: 300px;">
        </select>
        <br>
        <label for="date_debut">Date de l'activité pratique :</label>
                      <input type="date" id="date_debut" name="date_debut" required>
        <br>
        <input type="hidden" id="date_fin" name="date_fin">
        
    <br>

        <div class="pied-formulaire">
        <input type="submit" value="Soumettre">
        </div>
    <br>
    <br>
    </form>
</div>


<script>
        document.addEventListener("DOMContentLoaded", function() {
            const dateDebutInput = document.getElementById("date_debut");
            const dateFinInput = document.getElementById("date_fin");

            // Fonction pour mettre à jour la date_fin avec la date_debut
            function updateDateFin() {
                dateFinInput.value = dateDebutInput.value;
            }

            // Appel de la fonction 
            updateDateFin();

            // Écoutez les modifications de la date_debut et mettez à jour la date_fin 
            dateDebutInput.addEventListener("change", updateDateFin);
        });
    </script>    

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const actePrincipalSelect = document.getElementById("actePrincipal");
            const acteSecondaireSelect = document.getElementById("acteSecondaire");
            const orgSelect = document.getElementById("org");
            const catSelect = document.getElementById("cat");

            // Fonction pour récupérer les données et les remplir dans le menu déroulant
            function fetchActe() {
                const xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        const data = JSON.parse(this.responseText);
                        data.acte_principal.forEach(function(item) {
                            const option = document.createElement("option");
                            option.value = item.nom_actePrincipal;
                            option.textContent = item.nom_actePrincipal;
                            actePrincipalSelect.appendChild(option);
                        });
                        data.acte_secondaire.forEach(function(item) {
                            const option = document.createElement("option");
                            option.value = item.nom_acteSecondaire;
                            option.textContent = item.nom_acteSecondaire;
                            acteSecondaireSelect.appendChild(option);
                        });
                        data.acte_org.forEach(function(item) {
                            const option = document.createElement("option");
                            option.value = item.nom_org;
                            option.textContent = item.nom_org;
                            orgSelect.appendChild(option);
                        });
                        data.acte_cat.forEach(function(item) {
                            const option = document.createElement("option");
                            option.value = item.nom_cat;
                            option.textContent = item.nom_cat;
                            catSelect.appendChild(option);
                        });
                    }
                };
                xhttp.open("POST", "/php/data_profiling.php", true);
                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhttp.send(); 
            }

            
            fetchActe();
    });
</script>


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