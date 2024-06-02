<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Réseau</title>

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
            <?php if (isset($_SESSION['photo'])): ?>
                <img src="<?php echo htmlspecialchars($_SESSION['photo']); ?>" alt="Photo de profil" class="profile-photo">
            <?php endif; ?>
            <p class="welcome-text">Bienvenue, <?php echo htmlspecialchars($_SESSION['nom']); ?>!</p>
        </div>
    </header>
    <nav class="navigation">
        <a href="http://localhost/piscine1/accueil.php">
            <button type="button" class="btn-custom btn btn-primary">Accueil</button>
        </a>
        <a href="http://localhost/piscine1/reseau.php">
            <button type="button" class="btn-custom btn btn-primary active-page">Mon réseau</button>
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
            <div class="col-sm-8">
                <!-- afficher les profils des amis -->
                <?php
                $database = "reseau";
                $db_handle = mysqli_connect('localhost', 'root', '', $database);

                if ($db_handle) {
                    $sql = "SELECT profil.id, profil.photo, profil.nom, profil.description 
                            FROM profil 
                            JOIN ami ON profil.id = ami.id_user2 
                            JOIN ami AS ami_inverse ON profil.id = ami_inverse.id_user1 
                            WHERE ami.id_user1 = $user_id 
                            AND ami_inverse.id_user2 = $user_id 
                            AND ami.statut = 1 
                            AND ami_inverse.statut = 1";
                    $result = mysqli_query($db_handle, $sql);

                    if ($result) {
                        while ($data = mysqli_fetch_assoc($result)) {
                            $friend_id = htmlspecialchars($data['id']);
                            $image = htmlspecialchars($data['photo']);
                            $name = htmlspecialchars($data['nom']);
                            $description = htmlspecialchars($data['description']);
                            echo "<div class='profile-container'>";
                            echo "<div class='profile-image'>";
                            echo "<a href='page_ami.php?id=$friend_id'><img src='$image' alt='Photo de $name' class='profile-photo'></a><br>";
                            echo "<strong>$name</strong>";
                            echo "</div>";
                            echo "<div class='profile-description'>";
                            echo "<p>$description</p>";
                            echo "</div>";
                            echo "</div>";
                        }
                    } else {
                        echo "Erreur lors de l'exécution de la requête : " . mysqli_error($db_handle);
                    }

                } else {
                    echo "Database not found";
                }
                ?>
            </div>
            <div class="col-sm-4">
                <div class="text-right">
                    <br>
                    <a href="ajouter_ami.php">
                        <button type="button" class="btn-custom btn btn-success">Ajouter un ami</button>
                    </a>
                    <br><br>
                    <!-- demande ami -->
                    <h4>Demandes d'amis en attente</h4>
                    <div class="profile-container">
                    <?php
                        $db_handle = mysqli_connect('localhost', 'root', '', 'reseau');

                        if ($db_handle) {
                            $sql = "SELECT profil.id, profil.photo, profil.nom, profil.description
                                    FROM profil 
                                    JOIN ami ON profil.id = ami.id_user1 
                                    WHERE ami.id_user2 = $user_id AND ami.statut IS NULL";
                            $result = mysqli_query($db_handle, $sql);

                            if ($result) {
                                while ($data = mysqli_fetch_assoc($result)) {
                                    $friend_id = htmlspecialchars($data['id']);
                                    $image = htmlspecialchars($data['photo']);
                                    $name = htmlspecialchars($data['nom']);
                                    
                                    echo "<div class='profile-container'>";
                                    echo "<div class='profile-image'>";
                                    echo "<a href='accepter_demande.php?id1=$friend_id&id2=$user_id'><img src='$image' alt='Photo de $name' class='profile-photoH'></a><br>";
                                    echo "<strong>$name</strong>";
                                    echo "</div>";
                                    echo "<div>";
                                    echo "<a href='accepter_demande.php?id1=$friend_id' class='btn btn-success'>Accepter la demande</a>";
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
                    <h4>Propositions d'amis d'amis</h4>
                    <?php
                        $db_handle = mysqli_connect('localhost', 'root', '', 'reseau');

                        if ($db_handle) {
                            $sql = "SELECT DISTINCT p.id, p.photo, p.nom
                                    FROM profil p
                                    JOIN ami a1 ON p.id = a1.id_user2
                                    JOIN ami a2 ON a1.id_user1 = a2.id_user2
                                    WHERE a2.id_user1 = $user_id
                                    AND p.id NOT IN (SELECT id_user2 FROM ami WHERE id_user1 = $user_id)
                                    AND p.id != $user_id"; 
                            $result = mysqli_query($db_handle, $sql);

                            if ($result) {
                                while ($data = mysqli_fetch_assoc($result)) {
                                    $friend_id = htmlspecialchars($data['id']);
                                    $image = htmlspecialchars($data['photo']);
                                    $name = htmlspecialchars($data['nom']);
                                    
                                    echo "<div class='profile-container'>";
                                    echo "<div class='profile-image'>";
                                    echo "<a href='page_ami.php?id=$friend_id&id2=$user_id'><img src='$image' alt='Photo de $name' class='profile-photoH'></a><br>";
                                    echo "<strong>$name</strong>";
                                    echo "</div>";
                                    echo "<div>";
                                    echo "<a href='ajouter_ami2.php?id=$friend_id&id2=$user_id' class='btn btn-primary'>Ajouter</a>";
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
