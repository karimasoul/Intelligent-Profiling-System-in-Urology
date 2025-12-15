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
    
    <title>CARES 3.0</title>
    <style>
   
         
   .content {
        font-size: 250% !important ;
    font-weight: 500;
    color: black;
    padding: 12px 60px;
    }
    .centered {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}
form .separation {
  width: 100%;
  height: 1px;
  background-color: #1404c0;
}
form .corps-formulaire {
  display: flex;
  flex-wrap: wrap;
  margin-bottom: 30px;
}
form .corps-formulaire .groupe {
  position: relative; 
  margin-top: 20px;
  display: flex;
  flex-direction: column;
}
form .corps-formulaire .gauche .groupe input {
  margin-top: 5px;
  padding: 10px 5px 10px 30px;
  border: 2px solid #1404c0;
  outline-color: #1404c0;
  border-radius: 5px;
}
form .corps-formulaire .gauche .groupe i {
  position: absolute; 
  left: 0;
  top: 25px;
  padding: 9px 8px;
  color: #1404c0;
}
form .corps-formulaire .droite {
  margin-left: 40px;
  
}
form .corps-formulaire .droite .groupe {
  height: 100%;
}
form .corps-formulaire .droite .groupe textarea {
  margin-top: 5px;
  padding: 10px;
  background-color: #f1f1f1;
  border: 2px solid #1404c0;
  outline: none;
  border-radius: 5px;
  resize: none;
  height: 72%;
  width: 200%;
  
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
form .pied-formulaire button:hover {
  transform: scale(1.05);
}

@media screen and (max-width: 920px) {
  form .corps-formulaire .droite {
    margin-left: 0px;
  }
}

        
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
*{
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}

:root{
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

body{
    min-height: 100vh;
    background-color: var(--body-color);
    transition: var(--tran-05);
}

::selection{
    background-color: var(--primary-color);
    color: #fff;
}

body.dark{
    --body-color: #18191a;
    --sidebar-color: #242526;
    --primary-color: #3a3b3c;
    --primary-color-light: #3a3b3c;
    --toggle-color: #fff;
    --text-color: #ccc;
}


 .sidebar{
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
.sidebar.close{
    width: 88px;
}


.sidebar li{
    height: 50px;
    list-style: none;
    display: flex;
    align-items: center;
    margin-top: 10px;
}

.sidebar header .image,
.sidebar .icon{
    min-width: 60px;
    border-radius: 6px;
}

.sidebar .icon{
    min-width: 60px;
    border-radius: 6px;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.sidebar .text,
.sidebar .icon{
    color: var(--text-color);
    transition: var(--tran-03);
}

.sidebar .text{
    font-size: 17px;
    font-weight: 500;
    white-space: nowrap;
    opacity: 1;
}
.sidebar.close .text{
    opacity: 0;
}


.sidebar header{
    position: relative;
}

.sidebar header .image-text{
    margin-top: 20px;
    display: flex;
    align-items: center;
}
.sidebar header .logo-text{
    display: flex;
    flex-direction: column;
}
header .image-text .name {
    margin-top: 2px;
    font-size: 18px;
    font-weight: 600;
}


.sidebar header .image{
    display: flex;
    align-items: center;
    justify-content: center;
}

.sidebar header .image img{
    width: 80px;
    border-radius: 6px;
}

.sidebar header .toggle{
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

body.dark .sidebar header .toggle{
    color: var(--text-color);
}

.sidebar.close .toggle{
    transform: translateY(-50%) rotate(0deg);
}

.sidebar .menu{
    margin-top: 40px;
}


.sidebar li a{
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

.sidebar li a:hover{
    background-color: var(--primary-color);
}
.sidebar li a:hover .icon,
.sidebar li a:hover .text{
    color: var(--sidebar-color);
}
body.dark .sidebar li a:hover .icon,
body.dark .sidebar li a:hover .text{
    color: var(--text-color);
}

.sidebar .menu-bar{
    height: calc(100% - 55px);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    overflow-y: scroll;
}
.menu-bar::-webkit-scrollbar{
    display: none;
}
.sidebar .menu-bar .mode{
    border-radius: 6px;
    background-color: var(--primary-color-light);
    position: relative;
    transition: var(--tran-05);
}

.menu-bar .mode .sun-moon{
    height: 50px;
    width: 60px;
}

.mode .sun-moon i{
    position: absolute;
}
.mode .sun-moon i.sun{
    opacity: 0;
}
body.dark .mode .sun-moon i.sun{
    opacity: 1;
}
body.dark .mode .sun-moon i.moon{
    opacity: 0;
}

.menu-bar .bottom-content .toggle-switch{
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
.toggle-switch .switch{
    position: relative;
    height: 22px;
    width: 40px;
    border-radius: 25px;
    background-color: var(--toggle-color);
    transition: var(--tran-05);
}

.switch::before{
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

body.dark .switch::before{
    left: 20px;
}

.home{
    position: absolute;
    top: 0;
    top: 0;
    left: 250px;
    height: 100vh;
    width: calc(100% - 250px);
    background-color: var(--body-color);
    transition: var(--tran-05);
}
.home .text{
    font-size: 30px !important;
    font-weight: 500;
    color: var(--text-color);
    padding: 12px 60px;
}

.sidebar.close ~ .home{
    left: 78px;
    height: 100vh;
    width: calc(100% - 78px);
}
body.dark .home .text{
    color: var(--text-color);
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
            <i class='bx bx-bell icon ' style="background-color: red;"></i>
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
            <center><h1 class="colors">Demande de congé</h1></center>
            
            <!-- Formulaire -->
            <form class="colors" action="/php/traitement_congée_resident.php" method="post" enctype="multipart/form-data" >
               
                <div class="separation"></div>
                <div class="corps-formulaire">
                  <div class="gauche">
                    <div class="groupe">
                        <br>
                      <label for="date_debut">Date de début :</label>
                      <input type="date" id="date_debut" name="date_debut" required>
                      <i class="fas fa-user"></i>
                    </div>
                    <div class="groupe">
                        <label for="date_fin">Date de fin :</label>
                        <input type="date" id="date_fin" name="date_fin" required>
                      <i class="fas fa-envelope"></i>
                    </div>
                    <div class="groupe">
                      <label></label>
                      
                    </div>
                  </div>
          
                  <div class="droite">
                    <div class="groupe">
                        <br>
                      <label>Justificatif :</label>
                      <textarea id="justificatif" name="justificatif"  required placeholder="Veuillez saisir votre justificatif ici..."></textarea>
                      
                    </div>
                  </div>
                </div>
          
                <div class="pied-formulaire" align="center">
                  <input type="submit" value="Envoyer la demande">
                </div>
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