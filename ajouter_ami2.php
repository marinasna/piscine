<?php
session_start();

if (!isset($_SESSION['id']) || !isset($_GET['id'])) {
    header("Location: role.html");
    exit();
}

$user_id = $_SESSION['id'];
$friend_id = $_GET['id'];

$database = "reseau";
$db_handle = mysqli_connect('localhost', 'root', '', $database);

if ($db_handle) {
    // Insertion de la relation d'amitié dans les deux sens avec statut NULL
    $sql1 = "INSERT INTO ami (id_user1, id_user2, statut) VALUES ($user_id, $friend_id, NULL)";
    $sql2 = "INSERT INTO ami (id_user1, id_user2, statut) VALUES ($friend_id, $user_id, 1)";
    
    if (mysqli_query($db_handle, $sql1) && mysqli_query($db_handle, $sql2)) {
        header("Location: reseau.php");
    } else {
        echo "Erreur: " . mysqli_error($db_handle);
    }

    mysqli_close($db_handle);
} else {
    echo "Database not found";
}
?>
