<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: role.html");
    exit();
}

$database = "reseau";
$db_handle = mysqli_connect('localhost', 'root', '', $database);

$search_result = null;
$error_message = "";

if ($db_handle) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $search_query = mysqli_real_escape_string($db_handle, $_POST['search_query']);
        
        // Rechercher l'utilisateur par nom ou pseudo
        $sql = "SELECT profil.id, profil.photo, profil.nom, utilisateur.pseudo, profil.description 
                FROM profil 
                JOIN utilisateur ON profil.id = utilisateur.id 
                WHERE profil.nom LIKE '%$search_query%' OR utilisateur.pseudo LIKE '%$search_query%'";
        $result = mysqli_query($db_handle, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $search_result = mysqli_fetch_assoc($result);
        } else {
            $error_message = "Aucun utilisateur trouvé avec ce nom ou pseudo.";
        }
    }
    mysqli_close($db_handle);
} else {
    $error_message = "Database not found";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un ami</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="piscine.css">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-sm-8">
            <h2>Ajouter un ami</h2>
            <form action="ajouter_ami.php" method="POST" class="form-inline">
                <div class="form-group">
                    <label for="search_query">Nom ou Pseudo:</label>
                    <input type="text" class="form-control" id="search_query" name="search_query" required>
                </div>
                <br>
                <button type="submit" class="btn btn-custom">Rechercher</button>
            </form>
            <br>
            <?php if ($search_result): ?>
                <div class='profile-container'>
                    <div class='profile-image'>
                        <img src='<?php echo htmlspecialchars($search_result["photo"]); ?>' alt='Photo de <?php echo htmlspecialchars($search_result["nom"]); ?>' width='80' height='80'>
                    </div>
                    <div class='profile-description'>
                        <strong><?php echo htmlspecialchars($search_result["nom"]); ?> (<?php echo htmlspecialchars($search_result["pseudo"]); ?>)</strong>
                        <p><?php echo htmlspecialchars($search_result["description"]); ?></p>
                    </div>
                    <div class='profile-add'>
                        <a href='ajouter_ami2.php?id=<?php echo htmlspecialchars($search_result["id"]); ?>' class='btn btn-custom'>Ajouter</a>
                    </div>
                </div>
            <?php elseif ($error_message): ?>
                <div class="alert alert-danger"><?php echo $error_message; ?></div>
            <?php endif; ?>
        </div>
        <div class="col-sm-4 text-right">
            <a href="reseau.php" class="btn btn-custom">Retour</a>
        </div>
    </div>
</div>
</body>
</html>
