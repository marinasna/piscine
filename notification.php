<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <!-- Dernier CSS compilé et minifié -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <!-- Bibliothèque jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <!-- Dernier JavaScript compilé -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="piscine.css">
    <style>
        .profile-photo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
        }
        .welcome-text {
            color: white;
        }
        .notification {
            background-color: #f5f5f5;
            border: 1px solid #ddd;
            padding: 15px;
            margin: 10px 0;
        }
        .btn-custom, .btn-custom:hover, .btn-custom:focus {
            background-color: #245DB0; /* couleur bleue */
            color: white;
            border: none;
            margin-bottom: 10px;
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
                <button type="button" class="btn-custom btn btn-primary active-page">Notifications</button>
            </a>
            <a href="http://localhost/piscine1/message.php">
                <button type="button" class="btn-custom btn btn-primary">Messagerie</button>
            </a>
            <a href="http://localhost/piscine1/emplois.php">
                <button type="button" class="btn-custom btn btn-primary">Emplois</button>
            </a>
        </nav>
        <main class="main-content">
            <!-- boutons de filtrage -->
            <p>
                <a href="notification.php?filter=all">
                    <button type="button" class="btn-custom">Tout afficher</button>
                </a>
                &nbsp&nbsp&nbsp
                <a href="notification.php?filter=friends">
                    <button type="button" class="btn-custom">Événements d'amis</button>
                </a>
                &nbsp&nbsp&nbsp
                <a href="notification.php?filter=school">
                    <button type="button" class="btn-custom">Événements de l'école</button>
                </a>
                &nbsp&nbsp&nbsp
                <a href="notification.php?filter=friends_of_friends">
                    <button type="button" class="btn-custom">Événements d'amis d'amis</button>
                </a>
            </p>
            <div class="row">
                <div class="col-sm-12">
                    <?php
                    $database = "reseau";
                    $db_handle = mysqli_connect('localhost', 'root', '', $database);

                    if ($db_handle) {
                        // Récupérer les ID des amis de l'utilisateur connecté
                        $sql_friends = "SELECT id_user2 FROM ami WHERE id_user1 = $user_id AND statut = 1
                                        UNION
                                        SELECT id_user1 FROM ami WHERE id_user2 = $user_id AND statut = 1";
                        $result_friends = mysqli_query($db_handle, $sql_friends);

                        $friend_ids = [];
                        if ($result_friends) {
                            while ($row_friends = mysqli_fetch_assoc($result_friends)) {
                                $friend_ids[] = $row_friends['id_user2'];
                            }
                        }

                        $filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
                        $sql = "SELECT * FROM evenement";

                        if ($filter == 'friends' && !empty($friend_ids)) {
                            $friend_ids_str = implode(',', $friend_ids);
                            $sql .= " WHERE organisateurID IN ($friend_ids_str)";
                        } elseif ($filter == 'school') {
                            $sql .= " WHERE organisateurID = 0";
                        } elseif ($filter == 'friends_of_friends' && !empty($friend_ids)) {
                            $friend_ids_str = implode(',', $friend_ids);
                            $friends_of_friends_sql = "SELECT DISTINCT id_user2 FROM ami WHERE id_user1 IN ($friend_ids_str) AND statut = 1
                                                       UNION
                                                       SELECT DISTINCT id_user1 FROM ami WHERE id_user2 IN ($friend_ids_str) AND statut = 1";
                            $result_friends_of_friends = mysqli_query($db_handle, $friends_of_friends_sql);
                            $friends_of_friends_ids = [];

                            if ($result_friends_of_friends) {
                                while ($row_fof = mysqli_fetch_assoc($result_friends_of_friends)) {
                                    if ($row_fof['id_user2'] != $user_id && !in_array($row_fof['id_user2'], $friend_ids)) {
                                        $friends_of_friends_ids[] = $row_fof['id_user2'];
                                    }
                                }
                            }

                            if (!empty($friends_of_friends_ids)) {
                                $friends_of_friends_str = implode(',', $friends_of_friends_ids);
                                $sql .= " WHERE organisateurID IN ($friends_of_friends_str)";
                            } else {
                                $sql .= " WHERE 1 = 0"; // Aucun ami d'ami trouvé
                            }
                        }

                        $result = mysqli_query($db_handle, $sql);

                        if ($result) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<div class='notification'>";
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
