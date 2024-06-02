<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messagerie</title>
    <!-- Dernier CSS compilé et minifié -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <!-- Bibliothèque jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <!-- Dernier JavaScript compilé -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="piscine.css">
    <style>
        .message {
            background-color: #f5f5f5;
            border: 1px solid #ddd;
            padding: 15px;
            margin: 10px 0;
        }
        .message-sent {
            text-align: right;
        }
        .message-received {
            text-align: left;
        }
        .btn-custom, .btn-custom:hover, .btn-custom:focus {
            background-color: #007bff;
            color: white;
            border: none;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <?php
    session_start();

    if (isset($_SESSION['id'])) {
        $user_id = $_SESSION['id'];
        $pseudo = $_SESSION['pseudo'];
        $email = $_SESSION['email'];
        $photo_profil = $_SESSION['photo'];
        $nom = $_SESSION['nom'];
    } else {
        header("Location: role.html");
        exit();
    }

    $database = "reseau";
    $db_handle = mysqli_connect('localhost', 'root', '', $database);

    $selected_friend_id = isset($_GET['friend_id']) ? intval($_GET['friend_id']) : null;
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
                <button type="button" class="btn-custom btn btn-primary active-page">Messagerie</button>
            </a>
            <a href="http://localhost/piscine1/emplois.php">
                <button type="button" class="btn-custom btn btn-primary">Emplois</button>
            </a>
        </nav>
        <main class="main-content">
            <div class="row">
                <div class="col-sm-4">
                    <h4>Choisissez un ami :</h4>
                    <ul class="list-group">
                        <?php
                        $sql_friends = "SELECT DISTINCT p.id, p.nom FROM ami a 
                                        JOIN profil p ON (a.id_user2 = p.id AND a.id_user1 = $user_id) 
                                        OR (a.id_user1 = p.id AND a.id_user2 = $user_id)
                                        WHERE a.statut = 1";
                        $result_friends = mysqli_query($db_handle, $sql_friends);

                        if ($result_friends) {
                            while ($friend = mysqli_fetch_assoc($result_friends)) {
                                echo '<li class="list-group-item"><a href="message.php?friend_id=' . $friend['id'] . '">' . htmlspecialchars($friend['nom']) . '</a></li>';
                            }
                        } else {
                            echo '<li class="list-group-item">Vous n\'avez pas encore d\'amis.</li>';
                        }
                        ?>
                    </ul>
                </div>
                <div class="col-sm-8">
                    <?php
                    if ($selected_friend_id && $db_handle) {
                        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['message'])) {
                            $message = mysqli_real_escape_string($db_handle, $_POST['message']);
                            $date = date("Y-m-d H:i:s");
                            $sql_insert = "INSERT INTO message (id_envoyeur, id_receveur, message, date) VALUES ($user_id, $selected_friend_id, '$message', '$date')";
                            mysqli_query($db_handle, $sql_insert);
                        }

                        $sql_message = "SELECT * FROM message WHERE (id_envoyeur = $user_id AND id_receveur = $selected_friend_id) 
                                        OR (id_envoyeur = $selected_friend_id AND id_receveur = $user_id) ORDER BY date ASC";
                        $result_message = mysqli_query($db_handle, $sql_message);
                    ?>
                        <h4>Discussion avec <?php echo htmlspecialchars($selected_friend_id); ?></h4>
                        <?php if ($result_message): ?>
                            <?php while ($row = mysqli_fetch_assoc($result_message)): ?>
                                <div class="message <?php echo ($row['id_envoyeur'] == $user_id) ? 'message-sent' : 'message-received'; ?>">
                                    <p><strong><?php echo htmlspecialchars($row['id_envoyeur'] == $user_id ? 'Vous' : 'Ami'); ?>:</strong> <?php echo htmlspecialchars($row['message']); ?></p>
                                    <p><small><?php echo htmlspecialchars($row['date']); ?></small></p>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p>Vous n'avez pas encore de messages avec cet ami.</p>
                        <?php endif; ?>
                        <form method="POST" action="message.php?friend_id=<?php echo $selected_friend_id; ?>">
                            <div class="form-group">
                                <label for="message">Message:</label>
                                <textarea name="message" id="message" class="form-control" rows="3" required></textarea>
                            </div>
                            <button type="submit" class="btn-custom btn btn-primary">Envoyer</button>
                        </form>
                    <?php
                    } else {
                        echo '<p>Sélectionnez un ami pour commencer une conversation.</p>';
                    }
                    ?>
                </div>
            </div>
        </main>
        <footer class="footer">
            <p>ECE IN Paris &nbsp|&nbsp ecein.paris@gmail.com &nbsp|&nbsp 01 78 65 24 90 &nbsp|&nbsp 52 Avenue Sexius, Paris 75015</p>
        </footer>
    </div>
</body>
</html>
