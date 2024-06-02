<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil de l'ami</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="piscine.css">
</head>
<body>
<?php
// Démarrer la session
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['id'])) {
    // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    header("Location: role.html");
    exit();
}

// Récupérer l'ID de l'ami à partir de l'URL
if (isset($_GET['id'])) {
    $friend_id = intval($_GET['id']);
} else {
    echo "Ami non trouvé.";
    exit();
}

$database = "reseau";
$db_handle = mysqli_connect('localhost', 'root', '', $database);

if ($db_handle) {
    // Récupérer les informations de l'ami
    $sql = "SELECT photo, nom, description FROM profil WHERE id = $friend_id";
    $result = mysqli_query($db_handle, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);
        $image = htmlspecialchars($data['photo']);
        $name = htmlspecialchars($data['nom']);
        $description = htmlspecialchars($data['description']);
    } else {
        echo "Ami non trouvé.";
        mysqli_close($db_handle);
        exit();
    }

    mysqli_close($db_handle);
} else {
    echo "Database not found";
    exit();
}
?>
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <h2><?php echo $name; ?></h2>
            <img src="<?php echo $image; ?>" alt="Photo de <?php echo $name; ?>" class="profile-photo">
            <p><?php echo $description; ?></p>
        </div>
    </div>
</div>
</body>
</html>
