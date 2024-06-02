<?php
session_start();

// Récupération des valeurs des paramètres
$user_id = $_SESSION['id'];
$friend_id = $_GET['id1'];

// Connexion à la base de données
$database = "reseau";
$db_handle = mysqli_connect('localhost', 'root', '', $database);

if ($db_handle) {
    // Construction de la première requête SQL pour mettre à jour le statut dans le premier sens
    $sql1 = "UPDATE ami SET statut = 1 WHERE id_user1 = $friend_id AND id_user2 = $user_id AND statut IS NULL";

    // Exécution de la première requête SQL
    if (mysqli_query($db_handle, $sql1)) {
        // Construction de la deuxième requête SQL pour mettre à jour le statut dans le deuxième sens
        $sql2 = "UPDATE ami SET statut = 1 WHERE id_user1 = $user_id AND id_user2 = $friend_id AND statut IS NULL";

        // Exécution de la deuxième requête SQL
        if (mysqli_query($db_handle, $sql2)) {
            // Redirection vers la page de réseau après la mise à jour
            header("Location: reseau.php");
            exit(); // Assurez-vous de terminer le script après la redirection
        } else {
            echo "Erreur lors de la mise à jour du statut dans le deuxième sens : " . mysqli_error($db_handle);
            // Ajouter des informations de débogage pour afficher les valeurs des variables et la requête SQL
            echo "<br>";
            echo "Requête SQL : " . $sql2;
            echo "<br>";
            echo "ID utilisateur : " . $user_id . ", ID ami : " . $friend_id;
        }
    } else {
        echo "Erreur lors de la mise à jour du statut dans le premier sens : " . mysqli_error($db_handle);
        // Ajouter des informations de débogage pour afficher les valeurs des variables et la requête SQL
        echo "<br>";
        echo "Requête SQL : " . $sql1;
        echo "<br>";
        echo "ID utilisateur : " . $user_id . ", ID ami : " . $friend_id;
    }

    // Fermeture de la connexion à la base de données
    mysqli_close($db_handle);
} else {
    echo "Database not found";
}
?>
