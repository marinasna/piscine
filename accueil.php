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
    <link rel="stylesheet" type="text/css" href="accueil.css">
    <style>
        .profile-photo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
        }
    </style>
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
        <br>
        <div class="row" style="height:180px; background-color: #FD5B78">
            &nbsp &nbsp &nbsp
            <!-- logo -->
            <img src="img/m.gif" width="15%">
            &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp
            <!-- titre  -->
            <img src="img/logo.png">
            <!-- photo profil utilisateur -->
            <div class="pull-right">
                <br>
                <?php if ($photo_profil): ?>
                    <img src="<?php echo htmlspecialchars($photo_profil); ?>" alt="Photo de profil" class="profile-photo">
                <?php endif; ?>
                <br><br>
                <p >Bienvenue, <?php echo htmlspecialchars($nom) ; ?>!</p>
                &nbsp &nbsp &nbsp
            </div>
        </div>
        <!-- les boutons -->
        <div class="text-center">
                    <br>
                    <a href="http://localhost/piscine1/accueil.php">
                        <button type="button" class="btn-custom btn btn-primary">Accueil</button>
                    </a>
                    <a href="http://localhost/piscine1/reseau.php">
                        <button type="button" class="btn-custom btn btn-primary">Mon réseau</button>
                    </a>
                    <a href="http://localhost/piscine1/vous.php">
                        <button type="button" class="btn-custom btn btn-primary">Vous</button>
                    </a>
                    <a href="notif.html">
                        <button type="button" class="btn-custom btn btn-primary">Notifications</button>
                    </a>
                    <a href="message.html">
                        <button type="button" class="btn-custom btn btn-primary">Messagerie</button>
                    </a>
                    <a href="http://localhost/piscine1/emplois.php">
                        <button type="button" class="btn-custom btn btn-primary">Emplois</button>
                    </a>
                    <br>
                    <!-- rajouter bouton + d'options et pp etc-->
                </div>
        <!-- jusqu'ici-->
        <div class="row">
            <div class="col-sm-6">
                <p class="text-justify">
                    <br>
                    Cette plateforme est destinée aux personnes cherchant un emploi, un stage, une alternance ou autre.
                    Vous pouvez y trouver de nombreuses possibilités d’exploitation comme une messagerie, 
                    un espace dédié à vos connexions, c’est-à-dire les personnes avec qui vous êtes en relation ainsi qu’un espace offre d’emploi. 
                    N’attendez plus et créez votre propre profil afin de découvrir les offres d’emploi qui vous correspondent. <br><br>
                </p>
                <div id="myCarousel" class="carousel slide" data-ride="carousel">
                    <!-- Indicators -->
                    <ol class="carousel-indicators">
                        <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                        <li data-target="#myCarousel" data-slide-to="1"></li>
                        <li data-target="#myCarousel" data-slide-to="2"></li>
                        <li data-target="#myCarousel" data-slide-to="3"></li>
                    </ol>
                    <!-- Wrapper pour les images -->
                    <div class="carousel-inner">
                        <div class="item active">
                            <img src="img/francoiss.jpg" alt="francois" style="width:100%; height: 500px;">
                            <div class="carousel-caption">
                                <h3>Présentation de l'ECE</h3>
                                <p>Le directeur vous explique tout!</p>
                            </div>
                        </div>
                        <div class="item">
                            <img src="img/jpoo.jpg" alt="JPO" style="width:100%; height: 500px;">
                            <div class="carousel-caption">
                                <h3>JPO</h3>
                                <p>Venez découvrir le campus de Paris!</p>
                            </div>
                        </div>
                    </div>
                    <!-- Contrôles à gauche et à droite -->
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
                <!-- post -->
            </div>
        </div>
    </div>
</body>
</html>
