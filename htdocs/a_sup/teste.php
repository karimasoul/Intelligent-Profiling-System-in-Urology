<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultat du Calcul</title>
</head>
<body>
    <h2>Résultat du Calcul</h2>
    <?php
    // Récupérer les valeurs de 'a' et 'b' envoyées depuis le formulaire HTML
    $a = $_POST['num1'];
    $b = $_POST['num2'];

    // Exécuter le script Python avec les données 'a' et 'b' comme arguments
    $output = shell_exec("C:\\Users\\ouadk\\AppData\\Local\\Programs\\Python\\Python39\\python.exe C:\\Users\\ouadk\\PycharmProjects\\con_py_php\\main.py $a $b");

    // Afficher le résultat
    echo "<pre>$output</pre>";
    ?>
</body>
</html>
