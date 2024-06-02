<?php
// Démarrer la session
session_start();

// Vérifier si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifier si les champs pseudo et email existent
    if (isset($_POST["pseudo"], $_POST["email"])) {
        // Récupérer les valeurs saisies dans le formulaire
        $pseudo = $_POST["pseudo"];
        $email = $_POST["email"];
        
        // Connexion à la base de données
        $database = "reseau";
        $db_handle = mysqli_connect('localhost', 'root', '', $database);

        // Vérifier la connexion
        if ($db_handle) {
            // Préparer la requête SQL pour vérifier si le pseudo ou l'email existent déjà
            // et récupérer les informations de profil
            $sql = "
                SELECT u.id, u.pseudo, u.email, p.photo, p.nom
                FROM utilisateur u
                LEFT JOIN profil p ON u.id = p.id
                WHERE u.pseudo = '$pseudo' AND u.email = '$email'
            ";
            $result = mysqli_query($db_handle, $sql);

            // Vérifier s'il y a des résultats
            if (mysqli_num_rows($result) > 0) {
                // Récupérer les informations de l'utilisateur
                $user = mysqli_fetch_assoc($result);
                
                // Stocker les informations de l'utilisateur dans la session
                $_SESSION['id'] = $user['id'];
                $_SESSION['pseudo'] = $user['pseudo'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['photo'] = $user['photo'];
                $_SESSION['nom'] = $user['nom'];

                // Rediriger vers la page d'accueil
                header("Location: http://localhost/piscine1/accueil.php");
                exit(); // Arrêter le script pour éviter toute exécution supplémentaire
            } else {
                // Les données n'existent pas, rediriger vers la page du formulaire d'inscription
                header("Location: role.html");
                exit(); // Arrêter le script pour éviter toute exécution supplémentaire
            }
        } else {
            echo "Erreur de connexion à la base de données: " . mysqli_connect_error();
        }

        // Fermer la connexion à la base de données
        mysqli_close($db_handle);
    }
}
?>
