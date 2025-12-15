<?php
// Récupérer les données envoyées depuis le formulaire
$actePrincipale = $_POST['actePrincipale'];
$acteSecondaire = $_POST['acteSecondaire'];
$org = $_POST['org'];
$cat = $_POST['cat'];
$date_debut = $_POST['date_debut'];
$date_fin = $_POST['date_fin'];

$operateurAide = $_POST['operateurAide'];
$operateurAideId = $_POST['operateurAideId'];
$acteMaitrise = $_POST['acteMaitrise'];
$acteMaitriseId = $_POST['acteMaitriseId'];
$aideOperateur = $_POST['aideOperateur'];
$aideOperateurId = $_POST['aideOperateurId'];
$observateur = $_POST['observateur'];
$observateurId = $_POST['observateurId'];

session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "teste";

// Création de la connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}
//////////////////////////////////////////////////////////////////////////////////////////////
// Requête SQL pour récupérer l'ID de l'opérateur d'aide
$sql_operateur_aide = "SELECT id_personnel FROM members WHERE id = ?";
$stmt_operateur_aide = $conn->prepare($sql_operateur_aide);
$stmt_operateur_aide->bind_param("i", $operateurAideId);
$stmt_operateur_aide->execute();
$result_operateur_aide = $stmt_operateur_aide->get_result();
$operateur_aide_row = $result_operateur_aide->fetch_assoc();
$operateurAideId = $operateur_aide_row['id_personnel'];

// Requête SQL pour récupérer l'ID de l'acte de maîtrise
$sql_acte_maitrise = "SELECT id_personnel FROM members WHERE id = ?";
$stmt_acte_maitrise = $conn->prepare($sql_acte_maitrise);
$stmt_acte_maitrise->bind_param("i", $acteMaitriseId);
$stmt_acte_maitrise->execute();
$result_acte_maitrise = $stmt_acte_maitrise->get_result();
$acte_maitrise_row = $result_acte_maitrise->fetch_assoc();
$acteMaitriseId = $acte_maitrise_row['id_personnel'];

// Requête SQL pour récupérer l'ID de l'aide opérateur
$sql_aide_operateur = "SELECT id_personnel FROM members WHERE id = ?";
$stmt_aide_operateur = $conn->prepare($sql_aide_operateur);
$stmt_aide_operateur->bind_param("i", $aideOperateurId);
$stmt_aide_operateur->execute();
$result_aide_operateur = $stmt_aide_operateur->get_result();
$aide_operateur_row = $result_aide_operateur->fetch_assoc();
$aideOperateurId = $aide_operateur_row['id_personnel'];

// Requête SQL pour récupérer l'ID de l'observateur
$sql_observateur = "SELECT id_personnel FROM members WHERE id = ?";
$stmt_observateur = $conn->prepare($sql_observateur);
$stmt_observateur->bind_param("i", $observateurId);
$stmt_observateur->execute();
$result_observateur = $stmt_observateur->get_result();
$observateur_row = $result_observateur->fetch_assoc();
$observateurId = $observateur_row['id_personnel'];

/////////////////////////////////////////////////////////////////////////////////////////////
// Requête SQL pour récupérer les noms et prénoms des personnels
$sql_personnels = "SELECT id_personnel, CONCAT(nom_personnel, ' ', prenom_personnel) AS nom_complet FROM personnels";
$result_personnels = $conn->query($sql_personnels);

// Vérification s'il y a des résultats à afficher
if ($result_personnels->num_rows > 0) {
    // Stockage des résultats dans un tableau
    $personnels = array();
    while($row = $result_personnels->fetch_assoc()) {
        $personnels[] = $row;
    }
} else {
    // En cas d'absence de résultats
    $personnels = array();
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
    font-size: 82%;
    
    
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
           
            <form action="/php/manuel_remplire_acte_pratique.php" method="post">
                <input type="hidden" name="actePrincipale" value="<?php echo $actePrincipale; ?>">
                <input type="hidden" name="acteSecondaire" value="<?php echo $acteSecondaire; ?>">
                <input type="hidden" name="org" value="<?php echo $org; ?>">
                <input type="hidden" name="cat" value="<?php echo $cat; ?>">
                <input type="hidden" name="date_debut" value="<?php echo $date_debut; ?>">
                <input type="hidden" name="date_fin" value="<?php echo $date_fin; ?>">
                

                <input type="hidden" name="operateurAideId" value="<?php echo $operateurAideId; ?>">
<input type="hidden" name="acteMaitriseId" value="<?php echo $acteMaitriseId; ?>">
<input type="hidden" name="aideOperateurId" value="<?php echo $aideOperateurId; ?>">
<input type="hidden" name="observateurId" value="<?php echo $observateurId; ?>">

                <div class="corps-formulaire">
                    <div class="groupe gauche">
                        <div class="groupe">
                            <i class="bx bx-user icone-gauche"></i>
                            <label for="operateurAide">Operateur aide</label>
                            <select name="operateurAide" id="operateurAide">
                                <option value="">Sélectionner un opérateur d'aide</option>
                                <?php
                                
                                foreach ($personnels as $personnel) {
                                    echo "<option value='" . $personnel["id_personnel"] . "'";
                                    if ($personnel["id_personnel"] == $operateurAideId) {
                                        echo " selected";
                                    }
                                    echo ">" . $personnel["nom_complet"] . "</option>";
                                }
                                
                                ?>
                            </select>
                        </div>
                    
                        <div class="groupe">
                            <i class="bx bx-user icone-gauche"></i>
                            <label for="acteMaitrise">Acte Maitrise</label>
                            <select name="acteMaitrise" id="acteMaitrise">
                            <option value="">Sélectionner un Acte Maitrise</option>
                                <?php
                                
                                foreach ($personnels as $personnel) {
                                    echo "<option value='" . $personnel["id_personnel"] . "'";
                                    if ($personnel["id_personnel"] == $acteMaitriseId) {
                                        echo " selected";
                                    }
                                    echo ">" . $personnel["nom_complet"] . "</option>";
                                }
                                
                                ?>
                            </select>
                            
                        </div>
                    </div>
                    <div class="groupe gauche">
                        <div class="groupe">
                            <i class="bx bx-user"></i>
                            <label for="aideOperateur">Aide Operateur</label>
                            <select name="aideOperateur" id="aideOperateur">
                            <option value="">Sélectionner un Aide Operateur</option>
                                <?php
                                
                                foreach ($personnels as $personnel) {
                                    echo "<option value='" . $personnel["id_personnel"] . "'";
                                    if ($personnel["id_personnel"] == $aideOperateurId) {
                                        echo " selected";
                                    }
                                    echo ">" . $personnel["nom_complet"] . "</option>";
                                }
                                
                                ?>
                            </select>
                            
                        </div>
                    
                        <div class="groupe">
                            <i class="bx bx-user"></i>
                            <label for="observateur">Observateur</label>
                            <select name="observateur" id="observateur">
                            <option value="">Sélectionner un Observateur</option>
                                <?php
                                
                                foreach ($personnels as $personnel) {
                                    echo "<option value='" . $personnel["id_personnel"] . "'";
                                    if ($personnel["id_personnel"] == $observateurId) {
                                        echo " selected";
                                    }
                                    echo ">" . $personnel["nom_complet"] . "</option>";
                                }
                                
                                ?>
                            </select>
                            
                        </div>
                    </div>
                </div>
                <div class="pied-formulaire">
                    <input type="submit" value="Valider">
                </div>
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
    <script>
// Capture du formulaire lors de sa soumission
document.querySelector('form').addEventListener('submit', function(event) {
    // Récupération des valeurs sélectionnées dans chaque liste déroulante
    var operateurAide = document.getElementById('operateurAide').value;
    var acteMaitrise = document.getElementById('acteMaitrise').value;
    var aideOperateur = document.getElementById('aideOperateur').value;
    var observateur = document.getElementById('observateur').value;

    // Vérification des doublons
    if (operateurAide === acteMaitrise || operateurAide === aideOperateur || operateurAide === observateur ||
        acteMaitrise === aideOperateur || acteMaitrise === observateur ||
        aideOperateur === observateur) {
        // Affichage du message d'erreur
        alert("Veuillez choisir des noms différents pour chaque option.");
        // Annulation de l'envoi du formulaire
        event.preventDefault();
    }
});
</script>
</body>
</html>

    </section>
    
</body>
</html>