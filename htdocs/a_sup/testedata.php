<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Données du formulaire</title>
</head>
<body>
    <h2>Données reçues du formulaire :</h2>
    <p>Acte principal : <?php echo $_POST['actePrincipal']; ?></p>
    <p>Acte secondaire : <?php echo $_POST['acteSecondaire']; ?></p>
</body>
</html>
