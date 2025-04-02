<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registreren - Twitter Clone</title>
    <link rel="stylesheet" href="style.css">  <!-- Correcte CSS-link -->
</head>
<body>
<div class="left-section">

    <img src="twitter-logo-twitter-icon-twitter-symbol-free-free-vector.jpg" height="500" width="500"/></div>
<div class="right-section">
    <h2>Nu registreren</h2>
    <form action="register.php" method="POST" class="register-form">
        <div class="input-group">
            <label for="username">Gebruikersnaam:</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="input-group">
            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="input-group">
            <label for="password">Wachtwoord:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit" class="submit-btn">Account aanmaken</button>
    </form>
</div>
</body>
</html>



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
            header("Location: test.php"); // Redirect bij succes
            exit();
        } else {
            echo "Registratie mislukt. Probeer het opnieuw."; // Dit kan verder helpen bij foutopsporing
        }
    } catch (PDOException $e) {
        die("Database fout bij registratie: " . $e->getMessage());
    }
}
?>
