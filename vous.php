<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vous</title>
    <!-- Dernier CSS compilé et minifié -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <!-- Bibliothèque jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <!-- Dernier JavaScript compilé -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="piscine.css">
</head>
<body>
    <?php
    // Démarrer la session
    session_start();

    // Vérifier si l'utilisateur est connecté
    if (isset($_SESSION['id'])) {
        $user_id = $_SESSION['id'];
        $pseudo = $_SESSION['pseudo'];
        $email = $_SESSION['email'];
        $photo_profil = $_SESSION['photo'];
        $nom = $_SESSION['nom'];
    } else {
        // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
        header("Location: role.html");
        exit();
    }
    ?>
    <div class="container">
        <header class="header">
            <img src="img/logo.gif" alt="Logo" class="logo"> 
            <img src="img/ECEin.png" alt="Logo" class="logo">
            <div class="profile-info">
                <?php if ($photo_profil): ?>
                    <img src="<?php echo htmlspecialchars($photo_profil); ?>" alt="Photo de profil" class="profile-photo">
                <?php endif; ?>
                <p class="welcome-text">Bienvenue, <?php echo htmlspecialchars($nom); ?>!</p>
            </div>
        </header>
        <nav class="navigation">
            <a href="http://localhost/piscine1/accueil.php">
                <button type="button" class="btn-custom btn btn-primary">Accueil</button>
            </a>
            <a href="http://localhost/piscine1/reseau.php">
                <button type="button" class="btn-custom btn btn-primary">Mon réseau</button>
            </a>
            <a href="http://localhost/piscine1/vous.php">
                <button type="button" class="btn-custom btn btn-primary active-page">Vous</button>
            </a>
            <a href="http://localhost/piscine1/notification.php">
                <button type="button" class="btn-custom btn btn-primary">Notifications</button>
            </a>
            <a href="http://localhost/piscine1/message.php">
                <button type="button" class="btn-custom btn btn-primary">Messagerie</button>
            </a>
            <a href="http://localhost/piscine1/emplois.php">
                <button type="button" class="btn-custom btn btn-primary">Emplois</button>
            </a>
        </nav>
        <main class="main-content">
            <div class="profile-container">
                <div class="profile-image">
                    <?php if ($photo_profil): ?>
                        <img src="<?php echo htmlspecialchars($photo_profil); ?>" alt="Photo de profil" class="profile-photo">
                    <?php endif; ?>
                </div>
                <div class="profile-description">
                    <h2><?php echo htmlspecialchars($nom); ?></h2>
                    <p>Pseudo: <?php echo htmlspecialchars($pseudo); ?></p>
                    <p>Email: <?php echo htmlspecialchars($email); ?></p>
                    <!-- Ajoutez d'autres informations utilisateur ici -->
                </div>
            </div>

            <!-- Affichage des formations -->
            <div class="user-formation">
                <h3>Formations</h3>
                <?php
                // Connexion à la base de données
                $database = "reseau";
                $db_handle = mysqli_connect('localhost', 'root', '', $database);

                if ($db_handle) {
                    // Requête pour récupérer les formations de l'utilisateur
                    $sql = "SELECT * FROM formation WHERE id_user = $user_id";
                    $result = mysqli_query($db_handle, $sql);

                    if ($result) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<div class='panel panel-default'>";
                            echo "<div class='panel-heading'><h4>" . htmlspecialchars($row['titre']) . "</h4></div>";
                            echo "<div class='panel-body'>";
                            echo "<p><strong>Durée:</strong> " . htmlspecialchars($row['temps']) . " ans<br>";
                            echo "<strong>Description:</strong> " . htmlspecialchars($row['description']) . "<br>";
                            echo "<strong>Langue:</strong> " . htmlspecialchars($row['langue']) . "</p>";
                            echo "</div>";
                            echo "</div>";
                        }
                    } else {
                        echo "Erreur lors de l'exécution de la requête : " . mysqli_error($db_handle);
                    }

                    mysqli_close($db_handle);
                } else {
                    echo "Database not found";
                }
                ?>
            </div>
            <button type="button" class="btn-custom btn btn-primary">Générer mon CV</button>
        </main>
        <footer class="footer">
            <p>  ECE IN Paris &nbsp &nbsp|&nbsp &nbsp ecein.paris@gmail.com &nbsp &nbsp|&nbsp &nbsp 01 78 65 24 90 &nbsp &nbsp|&nbsp &nbsp
                52 Avenue Sexius, Paris 75015
            </p>
        </footer>
    </div>
</body>
</html>
