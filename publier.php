<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publier un événement</title>
    <link rel="stylesheet" type="text/css" href="connection.css">
</head>
<body>
    <div class="container">
        <div class="form-box">
            <h2>Publier un événement</h2>
            <?php
            // Démarrer la session
            session_start();

            // Connexion à la base de données
            $database = "reseau";
            $db_handle = mysqli_connect('localhost', 'root', '', $database);

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Vérifier que tous les champs sont remplis
                if (isset($_POST['organisateur'], $_POST['description'], $_POST['titre'], $_POST['lieu'], $_POST['date']) &&
                    !empty($_POST['organisateur']) && !empty($_POST['description']) && !empty($_POST['titre']) && !empty($_POST['lieu']) && !empty($_POST['date'])) {
                    
                    $titre = mysqli_real_escape_string($db_handle, $_POST['titre']);
                    $description = mysqli_real_escape_string($db_handle, $_POST['description']);
                    $lieu = mysqli_real_escape_string($db_handle, $_POST['lieu']);
                    $date = mysqli_real_escape_string($db_handle, $_POST['date']);
                    $organisateur = mysqli_real_escape_string($db_handle, $_POST['organisateur']);
                    if (isset($_SESSION['id'])) {
                        $user_id = $_SESSION['id'];
                    }
                    $organisateurID = $user_id;
                    
                    // Afficher la requête SQL pour le débogage
                    $sql_event = "INSERT INTO evenement (organisateur, description, titre, lieu, date, organisateurID) VALUES ('$organisateur', '$description', '$titre', '$lieu', '$date', '$organisateurID')";
                    

                    // Insérer dans la table evenement
                    if (mysqli_query($db_handle, $sql_event)) {
                        echo "<p class='success-message'>Événement publié avec succès! Vous allez être redirigé vers la page de connexion.</p>";
                        header("refresh:3;url=accueil.php"); // Redirection après 3 secondes
                    } else {
                        echo "<p class='error-message'>Erreur lors de l'insertion de l'événement: " . mysqli_error($db_handle) . "</p>";
                    }
                } else {
                    echo "<p class='error-message'>Veuillez remplir tous les champs.</p>";
                }
            }

            mysqli_close($db_handle);
            ?>
            <form action="publier.php" method="post">
                <label for="titre">Titre :</label>
                <input type="text" id="titre" name="titre" required>
                <label for="description">Description :</label>
                <textarea id="description" name="description" required></textarea>
                <label for="lieu">Lieu :</label>
                <input type="text" id="lieu" name="lieu" required>
                <label for="date">Date :</label>
                <input type="date" id="date" name="date" required>
                <label for="organisateur">Organisateur :</label>
                <input type="text" id="organisateur" name="organisateur" required>
                <input type="submit" value="Publier">
            </form>
        </div>
    </div>
</body>
</html>
