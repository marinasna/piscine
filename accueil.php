<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
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
                <button type="button" class="btn-custom btn btn-primary active-page">Accueil</button>
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
                <button type="button" class="btn-custom btn btn-primary">Emplois</button>
            </a>
        </nav>
        <main class="main-content">
            <div class="row">
                <div class="col-sm-6">
                    <p class="text-justify">
                        Cette plateforme est destinée aux personnes cherchant un emploi, un stage, une alternance ou autre.
                        Vous pouvez y trouver de nombreuses possibilités d’exploitation comme une messagerie, 
                        un espace dédié à vos connexions, c’est-à-dire les personnes avec qui vous êtes en relation ainsi qu’un espace offre d’emploi. 
                        N’attendez plus et créez votre propre profil afin de découvrir les offres d’emploi qui vous correspondent.
                    </p>
                    <h3> Événements de la semaine à l'ECE</h3>
                    <div id="myCarousel" class="carousel slide" data-ride="carousel">
                        <ol class="carousel-indicators">
                            <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                            <li data-target="#myCarousel" data-slide-to="1"></li>
                            <li data-target="#myCarousel" data-slide-to="2"></li>
                            <li data-target="#myCarousel" data-slide-to="3"></li>
                        </ol>
                        <div class="carousel-inner">
                            <div class="item active">
                                <img src="img/francois.png" alt="francois" style="width:100%; height: 500px;">
                                <div class="carousel-caption">
                                    <h3>Présentation de l'ECE</h3>
                                    <p>Le directeur vous explique tout!</p>
                                </div>
                            </div>
                            <div class="item">
                                <img src="img/locaux.png" alt="JPO" style="width:100%; height: 500px;">
                                <div class="carousel-caption">
                                    <h3>JPO</h3>
                                    <p>Venez découvrir le campus de Paris!</p>
                                </div>
                            </div>
                            <div class="item">
                                <img src="img/ambiance.png" alt="ambiance" style="width:100%; height: 500px;">
                                <div class="carousel-caption">
                                    <h3>Asso</h3>
                                    <p>L'ECE c'est aussi une bonne ambiance</p>
                                </div>
                            </div>
                            <div class="item">
                                <img src="img/danse.png" alt="danse" style="width:100%; height: 500px;">
                                <div class="carousel-caption">
                                    <h3>Scorpion à l'ECE</h3>
                                    <p>La semaine dernière on a eu un invité d'exception</p>
                                </div>
                            </div>
                        </div>
                        <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                            <span class="glyphicon glyphicon-chevron-left"></span>
                            <span class="sr-only">Précédent</span>
                        </a>
                        <a class="right carousel-control" href="#myCarousel" data-slide="next">
                            <span class="glyphicon glyphicon-chevron-right"></span>
                            <span class="sr-only">Suivant</span>
                        </a>
                    </div>
                </div>
                <div class="col-sm-6">
                    <!-- publier -->
                    <a href="publier.php">
                        <button type="submit" class="btn btn-primary btn-lg btn-block">Publier</button>
                    </a>

                    <h3>&nbsp;&nbsp;&nbsp;&nbsp;Événements personnels</h3>
                    <?php
                    $database = "reseau";
                    $db_handle = mysqli_connect('localhost', 'root', '', $database);

                    if ($db_handle) {
                        //  récupérer les événements personnels
                        $sql = "SELECT * FROM evenement WHERE organisateurID = $user_id ORDER BY date ASC";
                        $result = mysqli_query($db_handle, $sql);

                        if ($result) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<div class='job-notification'>";
                                echo "<h4>" . htmlspecialchars($row['titre']) . "</h4>";
                                echo "<p><strong>Lieu:</strong> " . htmlspecialchars($row['lieu']) . "<br>";
                                echo "<strong>Description:</strong> " . htmlspecialchars($row['description']) . "<br>";
                                echo "<strong>Date:</strong> " . htmlspecialchars($row['date']) . "<br>";
                                echo "<strong>Organisateur:</strong> " . htmlspecialchars($row['organisateur']) . "<br>";
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
                    <h3>&nbsp;&nbsp;&nbsp;&nbsp;Événements réseau</h3>
                    <?php
                    // Connexion à la base de données
                    $database = "reseau";
                    $db_handle = mysqli_connect('localhost', 'root', '', $database);

                    if ($db_handle) {
                        // Requête pour récupérer les événements réseau (événements organisés par les amis)
                        $sql = "SELECT evenement.* FROM evenement 
                                JOIN ami ON evenement.organisateurID = ami.id_user1
                                WHERE ami.id_user2 = $user_id ORDER BY evenement.date ASC";
                        $result = mysqli_query($db_handle, $sql);

                        if ($result) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<div class='job-notification'>";
                                echo "<h4>" . htmlspecialchars($row['titre']) . "</h4>";
                                echo "<p><strong>Lieu:</strong> " . htmlspecialchars($row['lieu']) . "<br>";
                                echo "<strong>Description:</strong> " . htmlspecialchars($row['description']) . "<br>";
                                echo "<strong>Date:</strong> " . htmlspecialchars($row['date']) . "<br>";
                                echo "<strong>Organisateur:</strong> " . htmlspecialchars($row['organisateur']) . "<br>";
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

