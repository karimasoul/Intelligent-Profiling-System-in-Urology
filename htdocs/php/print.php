<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "teste";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

// Récupération de la date de l'acte pratique fournie par l'user
$date_debut = $_POST['date_debut'];

// Requête SQL pour récupérer les données en fonction de la date
$sql = "SELECT r.id_rel, 
r.id_resid, 
c1.nom_cat, 
c2.nom_org, 
c3.nom_actePrincipal, 
c4.nom_acteSecondaire, 
r.role_resid, 
r.medecin_valid,
p.nom_personnel,
p.prenom_personnel 
FROM relation_actp_resid_med AS r
LEFT JOIN 
activite_pratique_categorie AS c1 ON r.id_apc = c1.id_apc
LEFT JOIN 
activite_pratique_organe AS c2 ON r.id_aporg = c2.id_aporg
LEFT JOIN 
activite_pratique_acteprincipal AS c3 ON r.id_apap = c3.id_apap
LEFT JOIN 
activite_pratique_actesecondaire AS c4 ON r.id_apas = c4.id_apas
LEFT JOIN 
members AS m ON r.id_resid = m.id
LEFT JOIN 
personnels AS p ON m.id_personnel = p.id_personnel 
 WHERE date_debut = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $date_debut);
$stmt->execute();
$result = $stmt->get_result();



if ($result->num_rows > 0) {           
// Création d'un objet PDF avec TCPDF
require_once('C:/apach24/Apache24/htdocs/vendor/tcpdf/tcpdf.php');

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Définition du nom du document PDF
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetTitle('Liste des activités pratiques');

// Ajout d'une page au document PDF
$pdf->AddPage();

// Entête du document PDF
$pdf->SetFont('dejavusans', '', 12);
$pdf->Write(0, 'Planification des activités pratiques pour le ' . $date_debut, '', 0, 'C', true, 0, false, false, 0);

// Traitement des données pour les afficher dans le PDF
$data = array();

// Extraction de la première ligne pour l'affichage initial
$row = mysqli_fetch_assoc($result);
$nom_prenom_resident = $row["nom_personnel"] . " " . $row["prenom_personnel"];
$data[] = array(
    'ID Résid' => $nom_prenom_resident,
    'ID APC' => $row['nom_cat'],
    'ID APORG' => $row['nom_org'],
    'ID APAP' => $row['nom_actePrincipal'],
    'ID APAS' => $row['nom_acteSecondaire'],
    'Rôle Résid' => $row['role_resid'],
    'Médecin Valid' => $row['medecin_valid']
);

while ($row = $result->fetch_assoc()) {
    
    $nom_prenom_resident = $row["nom_personnel"] . " " . $row["prenom_personnel"];
    $data[] = array(
        'ID Résid' => $nom_prenom_resident,
        'ID APC' => $row['nom_cat'],
        'ID APORG' => $row['nom_org'],
        'ID APAP' => $row['nom_actePrincipal'],
        'ID APAS' => $row['nom_acteSecondaire'],
        'Rôle Résid' => $row['role_resid'],
        'Médecin Valid' => $row['medecin_valid']
       
    );
}

// Affichage des données dans le PDF
// Création du tableau

$html = '<style>';
$html .= '.tableau-validation {';
$html .= '    width: 100%;';
$html .= '    border-collapse: collapse;';
$html .= '    margin: 20px;'; /* Ajouter des marges tout autour pour un meilleur espacement */
$html .= '    font-family: Arial, sans-serif;';
$html .= '    font-size: 14px;';
$html .= '    background-color: #f9f9f9;';
$html .= '}';
$html .= '.tableau-validation th {';
$html .= '    padding: 12px 15px;';
$html .= '    text-align: left;';
$html .= '    border: 1px solid #ddd;';
$html .= '    background-color: #0044cc;'; /* Fond de l\'en-tête */
$html .= '    color: white;'; /* Couleur du texte de l\'en-tête */
$html .= '}';
$html .= '.tableau-validation td {';
$html .= '    padding: 12px 15px;';
$html .= '    text-align: left;';
$html .= '    border: 1px solid #ddd;';
$html .= '}';
$html .= '.tableau-validation tr:nth-child(even) {';
$html .= '    background-color: #f2f2f2;'; /* Couleur de fond des lignes paires */
$html .= '}';
$html .= '.tableau-validation tr:hover {';
$html .= '    background-color: #e0e0e0;'; /* Couleur de fond au survol */
$html .= '}';
$html .= '</style>';
$html .= '<br>';
$html .= '<br>';
$html .= '<table class="tableau-validation">';
$html .= '<thead><tr><th>Nom Résident</th><th>Catégorie</th><th>Organe</th><th>Acte Principale</th><th>Acte Secondaire</th><th>Rôle Résident</th><th>Médecin Valid</th></tr></thead>';
foreach ($data as $row) {
    $html .= '<tr>';
    foreach ($row as $key => $value) {
        $html .= '<td >' . $value . '</td>';
    }
    $html .= '</tr>';
}
$html .= '</table>';

// Ajout du tableau au PDF
$pdf->writeHTML($html, true, false, true, false, '');

$pdf->Output('liste_activites.pdf', 'I');
} else {
    // Si aucune donnée n'est trouvée
    echo "Aucune donnée trouvée pour la date spécifiée.";
}
// Fermeture de la connexion à la base de données
$stmt->close();
$conn->close();



?>
