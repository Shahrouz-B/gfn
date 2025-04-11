<?php
session_start();  // Sitzung starten

// Überprüfen, ob der Benutzer abgemeldet wurde
if (isset($_GET['logout'])) {
    session_unset();  // Alle Sitzungsvariablen löschen
    session_destroy();  // Sitzung zerstören
    header("Location: ../HTML/index.php");  // Zurück zur Seite, um die Änderungen zu sehen
    exit();
}

// Wenn das Formular abgeschickt wurde, den Login-Prozess starten
if(isset($_POST['email'])){
    $servername = "localhost";
    $username = "copyshop";
    $password = "cppw";
    $dbname = "copyshop";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=copyshop", $username,$password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //echo "Connected successfully<br>";
        //return $conn;
        } catch(PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }

        $email = $_POST["email"];
        
        //echo"".$email."<br>";
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("SELECT * FROM `kunden` WHERE Email='$email'") ;
        $stmt->execute();
        //$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // set the resulting array to associative
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC); 
        $result = $result[0];
        //var_dump($result);
        $hash = $result["pwhash"];
        //echo "hash:  ".$hash."<br>";
        
        if(password_verify($_POST['pw'], $hash)){
            //echo 'Eingeloggt als<br>';
            $_SESSION['kunde'] = $result['Kunden_id'];
            //var_dump($_SESSION['kunde']);
            echo'<br>';
            //var_dump($result);
        }else{
            echo 'Login failed';
        }
        $conn= null;

    }

?>
</p>


<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mein Konto</title>

    <link rel="icon" href="../Bilder/icon.png" type="image/png">
    <link rel="stylesheet" href="../CSS/style.css">
    <link href="https://fonts.googleapis.com/css2?family=DynaPuff:wght@400..700&display=swap" rel="stylesheet">

</head>
<body>
    <?php include('../PHP/header.php'); ?>
    <div class="text-container">

        <?php
        if (isset($_SESSION["kunde"])) {
            echo "<h2>Willkommen, " . htmlspecialchars($vorname) . "! Du hast dich erfolgreich eingeloggt.</h2>";
            echo "<button class='styled-button' onclick=\"window.location.href='angaben.php';\">Angaben vervollständigen</button>";
        } else {
            echo "<h2>Anmeldung</h2>";
        }
        ?>     
    </div>

    <div class="contact-container">
        <?php
        // Login-Fehler anzeigen, falls vorhanden
        if (isset($login_error)) {
            echo '<p style="color: red;">' . $login_error . '</p>';
        }
        ?>

        <?php if (!isset($_SESSION["kunde"])): ?>
            <form action="" method="POST">
                <div class="form-group">
                    <label for="email">E-Mail:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Passwort:</label>
                    <input type="password" id="password" name="pw" required>
                </div>
                &nbsp;&nbsp;<button class="impressum" onclick="window.location.href='../HTML/account.html';">Passwort vergessen?</button><br><br>
                <button type="submit" class="styled-button">Anmelden</button>
            </form>
            <h3>Neu hier?</h3>
            <button class="styled-button" onclick="window.location.href='../PHP/register.php';">Registrieren</button>
        <?php endif; ?>
    </div>

    <div class="footer">
        <p class="footer">© <span id="year"></span> Pomodro Copyshop. Alle Rechte vorbehalten. 
            <button class="impressum" onclick="window.location.href='../HTML/impressum.php';">Impressum</button>
        </p>

        <script>
            document.getElementById("year").textContent = new Date().getFullYear();
        </script>
    </div>

</body>
</html>