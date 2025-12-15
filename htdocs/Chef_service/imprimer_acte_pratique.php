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

form {
    display: flex;
    align-items: center; 
    gap: 10px;
    margin-right: 30%;
}

form label {
    flex: 1; 
    text-align: right;
    margin-right: 10px; 
}

form input[type="date"] {
    width: 150px; 
    padding: 8px;
    border: 2px solid #1404c0;
    border-radius: 5px;
    outline: none;
}

form button[type="submit"] {
    padding: 8px 15px;
    background-color: #1404c0;
    color: white;
    border: none;
    border-radius: 5px;
    outline: none;
    cursor: pointer;
    transition: background-color 0.3s;
}


h1{
    color: var(--text-color);
}

.content {
        font-size: 250%;
    font-weight: 500;
    color: var(--text-color);
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
    color: var(--text-color);
}
    .circle {
    width: 200px;
    height: 200px;
    border-radius: 50%;
    background-color: #009DCF;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 24px;
    font-weight: bold;
    color: #fff;
    margin: 10px;
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
         <div class="centered">
           
            <h1>Planification des actes pratiques</h1>
            
        <br>
 
            <div>
             
             <form id="printForm" action="/php/print.php" method="post" target="pdfFrame">
        <label for="date_debut">Date de l'acte pratique :</label>
        <input type="date" id="date_debut" name="date_debut" required>
        <button type="submit">Afficher le PDF</button>
    </form>
            </div>
          </div>  
       
        
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