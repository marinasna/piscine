<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" type="text/css" href="connection.css">
</head>
<body>
    <div class="container">
        <div class="form-box">
            <h2>Inscription</h2>
            <?php
            // Connexion à la base de données
            $database = "reseau";
            $db_handle = mysqli_connect('localhost', 'root', '', $database);

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Vérifier que tous les champs sont remplis
                if (isset($_POST['email'], $_POST['pseudo'], $_POST['nom'], $_POST['description'], $_POST['photo']) &&
                    !empty($_POST['email']) && !empty($_POST['pseudo']) && !empty($_POST['nom']) && !empty($_POST['description']) && !empty($_POST['photo'])) {
                    
                    $email = mysqli_real_escape_string($db_handle, $_POST['email']);
                    $pseudo = mysqli_real_escape_string($db_handle, $_POST['pseudo']);
                    $nom = mysqli_real_escape_string($db_handle, $_POST['nom']);
                    $description = mysqli_real_escape_string($db_handle, $_POST['description']);
                    $photo_path = mysqli_real_escape_string($db_handle, $_POST['photo']);
                    
                    // Insérer dans la table utilisateur
                    $sql_user = "INSERT INTO utilisateur (role, email, pseudo) VALUES ('user', '$email', '$pseudo')";
                    if (mysqli_query($db_handle, $sql_user)) {
                        $user_id = mysqli_insert_id($db_handle);
                        
                        // Insérer dans la table profil
                        $sql_profile = "INSERT INTO profil (photo, description, nom) VALUES ('$photo_path', '$description', '$nom')";
                        if (mysqli_query($db_handle, $sql_profile)) {
                            echo "<p class='success-message'>Inscription réussie! Vous allez être redirigé vers la page de connexion.</p>";
                            header("refresh:3;url=role.html"); // Redirection après 3 secondes
                        } else {
                            echo "<p class='error-message'>Erreur lors de l'insertion dans la table profil: " . mysqli_error($db_handle) . "</p>";
                        }
                    } else {
                        echo "<p class='error-message'>Erreur lors de l'insertion dans la table utilisateur: " . mysqli_error($db_handle) . "</p>";
                    }
                } else {
                    echo "<p class='error-message'>Veuillez remplir tous les champs.</p>";
                }
            }

            mysqli_close($db_handle);
            ?>
            <form action="inscription.php" method="post">
                <label for="email">E-mail :</label>
                <input type="email" id="email" name="email" required>
                <label for="pseudo">Pseudo :</label>
                <input type="text" id="pseudo" name="pseudo" required>
                <label for="nom">Nom prénom :</label>
                <input type="text" id="nom" name="nom" required>
                <label for="description">Description :</label>
                <textarea id="description" name="description" required></textarea>
                <label for="photo">Chemin d'accès à la photo de profil :</label>
                <input type="text" id="photo" name="photo" required>
                <input type="submit" value="S'inscrire">
            </form>
        </div>
    </div>
</body>
</html>
