<?php
session_start(); // Assurez-vous que la session est démarrée

if (!isset($_SESSION['id'])) {
    header("HTTP/1.0 403 Forbidden");
    die("Accès refusé: veuillez vous connecter.");
}

$user_id = $_SESSION['id']; // ID de l'utilisateur connecté

// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = ""; // Utilisez votre mot de passe de MySQL
$dbname = "reseau";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

header("Content-Type: text/xml"); // Définir le contenu comme XML
echo "<?xml version='1.0' encoding='UTF-8'?>";
echo "<Root>";

// Requête pour récupérer les informations de l'utilisateur, son profil et ses formations
$sql = "SELECT u.email, p.nom, p.photo, p.description, f.titre, f.temps, f.description AS formation_desc, f.langue 
        FROM utilisateur u
        JOIN profil p ON u.id = p.id
        JOIN formation f ON u.id = f.id_user
        WHERE u.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<CV>";
        echo "<Nom>" . htmlspecialchars($row["nom"]) . "</Nom>";
        echo "<Email>" . htmlspecialchars($row["email"]) . "</Email>";
        echo "<PhotoProfil>" . htmlspecialchars($row["photo"]) . "</PhotoProfil>";
        echo "<Description>" . htmlspecialchars($row["description"]) . "</Description>";
        echo "<Formation>";
        echo "<Titre>" . htmlspecialchars($row["titre"]) . "</Titre>";
        echo "<Duree>" . htmlspecialchars($row["temps"]) . " ans</Duree>";
        echo "<DescriptionFormation>" . htmlspecialchars($row["formation_desc"]) . "</DescriptionFormation>";
        echo "<Langue>" . htmlspecialchars($row["langue"]) . "</Langue>";
        echo "</Formation>";
        echo "</CV>";
    }
} else {
    echo "<Message>Aucun utilisateur trouvé</Message>";
}

echo "</Root>";
$conn->close();
?>
