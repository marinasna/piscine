<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supprimer des auteurs</title>
    <!-- Dernier CSS compilé et minifié -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <!-- Bibliothèque jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <!-- Dernier JavaScript compilé -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>
    <?php
    // Vérifier si l'utilisateur est connecté et est un administrateur
    session_start();
    if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
        // Connexion à la base de données
        $database = "reseau";
        $db_handle = mysqli_connect('localhost', 'root', '', $database);

        if ($db_handle) {
            // Requête pour récupérer la liste des auteurs avec les détails du profil
            $sql = "SELECT utilisateur.id, utilisateur.pseudo, profil.nom, profil.photo 
                    FROM utilisateur 
                    INNER JOIN profil ON utilisateur.id = profil.id";
            $result = mysqli_query($db_handle, $sql);

            if ($result) {
                echo "<div class='container'>";
                echo "<h2>Liste des auteurs</h2>";
                echo "<div class='row'>";
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<div class='col-sm-4'>";
                    echo "<div class='panel panel-default'>";
                    echo "<div class='panel-heading'>" . $row['pseudo'] . "</div>";
                    echo "<div class='panel-body'>";
                    echo "<img src='" . $row['photo'] . "' alt='Photo de " . $row['nom'] . "' class='img-responsive'>";
                    echo "<p><br>Nom: " . $row['nom'] . "</p>";
                    echo "</div>";
                    echo "<div class='panel-footer'>";
                    echo "<form method='post' action='supprimer_auteur2.php'>";
                    echo "<input type='hidden' name='auteur_id' value='" . $row['id'] . "'>";
                    echo "<button type='submit' class='btn btn-danger'>Supprimer</button>";
                    echo "</form>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                }
                echo "</div>";
                echo "</div>";
            } else {
                echo "Erreur lors de l'exécution de la requête : " . mysqli_error($db_handle);
            }

            mysqli_close($db_handle);
        } else {
            echo "Database not found";
        }
    } else {
        echo "Vous n'êtes pas autorisé à accéder à cette page.";
    }
    ?>
</body>
</html>
