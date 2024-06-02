<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emplois</title>
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
                <button type="button" class="btn-custom btn btn-primary">Vous</button>
            </a>
            <a href="http://localhost/piscine1/notification.php">
                <button type="button" class="btn-custom btn btn-primary">Notifications</button>
            </a>
            <a href="http://localhost/piscine1/message.php">
                <button type="button" class="btn-custom btn btn-primary">Messagerie</button>
            </a>
            <a href="http://localhost/piscine1/emplois.php">
                <button type="button" class="btn-custom btn btn-primary active-page">Emplois</button>
            </a>
        </nav>
        <main class="main-content">
            <div class="row">
                <div class="col-sm-12">
                    <div class="btn-group-horizontal">
                        <a href="emplois.php">
                            <button type="button" class="btn btn-custom">Tout afficher</button>
                        </a>
                        <div class="dropdown">
                            <button class="btn btn-custom dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Trier
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <li><a class="dropdown-item" href="emplois.php?type=0">CDI</a></li>
                                <li><a class="dropdown-item" href="emplois.php?type=1">CDD</a></li>
                                <li><a class="dropdown-item" href="emplois.php?type=2">Stage</a></li>
                                <li><a class="dropdown-item" href="emplois.php?type=3">Alternance</a></li>
                            </ul>
                        </div>
                    </div>
                    <br><br>
                    <?php
                    $database = "reseau";
                    $db_handle = mysqli_connect('localhost', 'root', '', $database);

                    if ($db_handle) {
                        $type = isset($_GET['type']) ? intval($_GET['type']) : -1;
                        $sql = "SELECT * FROM emploi";
                        if ($type >= 0 && $type <= 3) {
                            $sql .= " WHERE type = $type";
                        }
                        $result = mysqli_query($db_handle, $sql);

                        if ($result) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<div class='job-notification'>";
                                echo "<h4>" . htmlspecialchars($row['titre']) . "</h4>";
                                echo "<p><strong>Lieu:</strong> " . htmlspecialchars($row['lieu']) . "<br>";
                                echo "<strong>Description:</strong> " . htmlspecialchars($row['description']) . "<br>";
                                echo "<strong>Date:</strong> " . htmlspecialchars($row['date']) . "<br>";
                                echo "<strong>Entreprise:</strong> " . htmlspecialchars($row['entreprise']) . "</p>";
                                echo "<strong>Expérience:</strong> " . htmlspecialchars($row['experience']) . "<br>";
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
            </div>
        </main>
        <footer class="footer">
            <p>  ECE IN Paris &nbsp &nbsp|&nbsp &nbsp ecein.paris@gmail.com &nbsp &nbsp|&nbsp &nbsp 01 78 65 24 90 &nbsp &nbsp|&nbsp &nbsp
                52 Avenue Sexius, Paris 75015
            </p>
        </footer>
    </div>
</body>
</html>
