<?php
$host = 'localhost';
$dbname = 'twitter_clone';
$username = 'root';
$password = '';

try {
    // Verbind met de database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    echo "Databaseverbinding geslaagd.<br>"; // Test de verbinding
} catch (PDOException $e) {
    die("Database verbinding mislukt: " . $e->getMessage());
}

// Controleer of de formulier wordt ingediend via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verkrijg en trim de gebruikersinvoer
    $username = trim($_POST['username']);
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];

    // Controleer of alle velden geldig zijn
    if (!$username || !$email || !$password) {
        die("Vul alstublieft een geldige gebruikersnaam, e-mailadres en wachtwoord in.");
    }

    // Controleer of het e-mailadres al in de database bestaat
    try {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            die("Dit e-mailadres is al geregistreerd.");
        }
    } catch (PDOException $e) {
        die("Database fout bij controleren e-mail: " . $e->getMessage());
    }


    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Probeer de gebruiker toe te voegen aan de database
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
        if ($stmt->execute([$username, $email, $hashed_password])) {
            echo "Registratie geslaagd!"; // Dit moet aangeven of de invoer succesvol was
            header("Location: tweet.html"); // Redirect bij succes
            exit();
        } else {
            echo "Registratie mislukt. Probeer het opnieuw."; // Dit kan verder helpen bij foutopsporing
        }
    } catch (PDOException $e) {
        die("Database fout bij registratie: " . $e->getMessage());
    }
}
?>
