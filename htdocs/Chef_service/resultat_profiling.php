<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Vérifier si le résultat est stocké dans la session
if (isset($_SESSION['output'])) {
    $output = $_SESSION['output'];
    // Supprimer le résultat de la session
    unset($_SESSION['output']);
} else {
    
    header("Location: Proc_profiling.php");
    exit;
}

$actePrincipale = $_SESSION['actePrincipale'];
$acteSecondaire = $_SESSION['acteSecondaire'];
$org = $_SESSION['org'];
$cat = $_SESSION['cat'];
$date_debut = $_SESSION['date_debut'];
$date_fin = $_SESSION['date_fin'];
//
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "teste";


$conn = mysqli_connect($servername, $username, $password, $dbname);


if (!$conn) {
    die("Échec de la connexion : " . mysqli_connect_error());
}

// Initialiser les variables 
$operateurAideId = $acteMaitriseId = $aideOperateurId = $observateurId = "";
$operateurAide = $acteMaitrise = $aideOperateur = $observateur = "";

// sortie du script Python 
$lines = explode("\n", trim($output));
foreach ($lines as $line) {
    if (preg_match('/Resident (\d+): (\w+)/', $line, $matches)) {
        $index = intval($matches[1]);
        $id_resid = htmlspecialchars($matches[2]);

        // Récupérer les infos du résident 
        $sql = "SELECT nom_personnel, prenom_personnel FROM personnels INNER JOIN members ON personnels.id_personnel = members.id_personnel WHERE members.id = '$id_resid'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $nom_prenom_resident = $row["nom_personnel"] . " " . $row["prenom_personnel"];

            // remplir les noms et prénoms aux variables avec l'index
            switch ($index) {
                case 1:
                    $operateurAideId = $id_resid;
                    $operateurAide = $nom_prenom_resident;
                    break;
                case 2:
                    $acteMaitriseId = $id_resid;
                    $acteMaitrise = $nom_prenom_resident;
                    break;
                case 3:
                    $aideOperateurId = $id_resid;
                    $aideOperateur = $nom_prenom_resident;
                    break;
                case 4:
                    $observateurId = $id_resid;
                    $observateur = $nom_prenom_resident;
                    break;
            }
        }
    }
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

        button{
            background-color: #1404c0 !important;
            border-radius: 5px;
            color: white;
        }
        .sidebar header .image img{
    width: 80px;
    border-radius: 6px;
}

.content {
        font-size: 250%;
    font-weight: 500;
    color: var(--text-color) !important;
    padding: 12px 60px;
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
    flex-direction: row; 
    align-items: center; 
  }
  form .corps-formulaire .groupe label {
    margin-right: 10px; 
  }
  form .corps-formulaire .groupe input,
  form .corps-formulaire .groupe textarea {
    margin-top: 5px;
    padding: 10px;
    border: 2px solid #1404c0;
    outline: none;
    border-radius: 5px;
  }
  form .corps-formulaire .groupe i {
    margin-left: 10px; 
    color: #1404c0;
  }
  form .pied-formulaire {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 20px;
  }
  form .pied-formulaire input[type="submit"] {
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

  form .corps-formulaire .groupe .icone-gauche {
    margin-right: 10px; 
    color: #1404c0;
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
                        <a href="#">
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
    <center><h2>Résultat du Profiling</h2></center>
    
    <form action="/php/remplire_acte_pratique_postprofiling.php" method="post">
    <input type="hidden" name="actePrincipale" value="<?php echo $actePrincipale; ?>">
    <input type="hidden" name="acteSecondaire" value="<?php echo $acteSecondaire; ?>">
    <input type="hidden" name="org" value="<?php echo $org; ?>">
    <input type="hidden" name="cat" value="<?php echo $cat; ?>">
    <input type="hidden" name="date_debut" value="<?php echo $date_debut; ?>">
    <input type="hidden" name="date_fin" value="<?php echo $date_fin; ?>">
    
                <div class="corps-formulaire">
                    <div class="groupe gauche">
                        <div class="groupe">
                            <i class="bx bx-user icone-gauche"></i>
                            <label for="operateurAide">Operateur aide</label>
                            <input type="text" name="operateurAide" id="operateurAide" value="<?php echo $operateurAide; ?>" readonly>
                            <input type="hidden" name="operateurAideId" value="<?php echo $operateurAideId; ?>">
                        </div>
                    </div>
                    <div class="groupe gauche">
                        <div class="groupe">
                            <i class="bx bx-user icone-gauche"></i>
                            <label for="acteMaitrise">Acte Maitrise</label>
                            <input type="text" name="acteMaitrise" id="acteMaitrise" value="<?php echo $acteMaitrise; ?>" readonly>
                            <input type="hidden" name="acteMaitriseId" value="<?php echo $acteMaitriseId; ?>">
                        </div>
                    </div>
                    <div class="groupe gauche">
                        <div class="groupe">
                            <i class="bx bx-user"></i>
                            <label for="aideOperateur">Aide Operateur</label>
                            <input type="text" name="aideOperateur" id="aideOperateur" value="<?php echo $aideOperateur; ?>" readonly>
                            <input type="hidden" name="aideOperateurId" value="<?php echo $aideOperateurId; ?>">
                        </div>
                    </div>
                    <div class="groupe gauche">
                        <div class="groupe">
                            <i class="bx bx-user"></i>
                            <label for="observateur">Observateur</label>
                            <input type="text" name="observateur" id="observateur" value="<?php echo $observateur; ?>" readonly>
                            <input type="hidden" name="observateurId" value="<?php echo $observateurId; ?>">
                        </div>
                    </div>
                </div>
                <div class="pied-formulaire">
                    <input type="submit" value="Valider">
                </div>
            </form>


            <!-- -->
            <form action="changer_resultat.php" method="post">
    <!-- Champs cachés pour envoyer les mêmes variables -->
    <input type="hidden" name="actePrincipale" value="<?php echo $actePrincipale; ?>">
    <input type="hidden" name="acteSecondaire" value="<?php echo $acteSecondaire; ?>">
    <input type="hidden" name="org" value="<?php echo $org; ?>">
    <input type="hidden" name="cat" value="<?php echo $cat; ?>">
    <input type="hidden" name="date_debut" value="<?php echo $date_debut; ?>">
    <input type="hidden" name="date_fin" value="<?php echo $date_fin; ?>">
    
    <!-- Champs cachés supplémentaires pour les données  du deuxième formulaire -->
    <input type="hidden" name="operateurAide" value="<?php echo $operateurAide; ?>">
    <input type="hidden" name="operateurAideId" value="<?php echo $operateurAideId; ?>">
    <input type="hidden" name="acteMaitrise" value="<?php echo $acteMaitrise; ?>">
    <input type="hidden" name="acteMaitriseId" value="<?php echo $acteMaitriseId; ?>">
    <input type="hidden" name="aideOperateur" value="<?php echo $aideOperateur; ?>">
    <input type="hidden" name="aideOperateurId" value="<?php echo $aideOperateurId; ?>">
    <input type="hidden" name="observateur" value="<?php echo $observateur; ?>">
    <input type="hidden" name="observateurId" value="<?php echo $observateurId; ?>">
    <!-- Bouton pour soumettre le formulaire -->
    <button type="submit">Changer manuelement</button>
</form>




</div>


    

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
