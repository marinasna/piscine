<?php
session_start();

// Vérifier si l'utilisateur est connecté et est un administrateur
if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    // Vérifier si l'identifiant de l'utilisateur à supprimer est défini dans la requête POST
    if (isset($_POST['auteur_id'])) {
        // Récupérer l'identifiant de l'utilisateur à supprimer
        $auteur_id = $_POST['auteur_id'];

        // Connexion à la base de données
        $database = "reseau";
        $db_handle = mysqli_connect('localhost', 'root', '', $database);

        if ($db_handle) {
            // Requête pour supprimer l'utilisateur de la table utilisateur
            $sql_delete_user = "DELETE FROM utilisateur WHERE id = $auteur_id";
            $result_delete_user = mysqli_query($db_handle, $sql_delete_user);

            // Requête pour supprimer l'utilisateur de la table profil
            $sql_delete_profile = "DELETE FROM profil WHERE id = $auteur_id";
            $result_delete_profile = mysqli_query($db_handle, $sql_delete_profile);

            if ($result_delete_user && $result_delete_profile) {
                echo "L'utilisateur a été supprimé avec succès.";
            } else {
                echo "Erreur lors de la suppression de l'utilisateur : " . mysqli_error($db_handle);
            }

            mysqli_close($db_handle);
        } else {
            echo "Database not found";
        }
    } else {
        echo "Identifiant de l'utilisateur non spécifié.";
    }
} else {
    echo "Vous n'êtes pas autorisé à accéder à cette page.";
}
?>
