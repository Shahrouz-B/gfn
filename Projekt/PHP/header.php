<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();  // Sitzung nur starten, wenn keine aktiv ist
}

// Überprüfen, ob der Benutzer abgemeldet wurde
if (isset($_GET['logout'])) {
    session_unset();  // Alle Sitzungsvariablen löschen
    session_destroy();  // Sitzung zerstören
    header("Location: ../HTML/index.php");  // Zurück zur Seite
    exit();
}
?>
<?php if (isset($_SESSION["kunde"])): ?>
    <?php
        $servername = "localhost";
        $username = "copyshop";
        $password = "cppw";
        $dbname = "copyshop";
        try {
            $conn = new PDO("mysql:host=$servername;dbname=copyshop", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }

        // Benutzerdaten abrufen
        $stmt = $conn->prepare("SELECT * FROM `kunden` WHERE Kunden_id = :kunde_id");
        $stmt->bindParam(':kunde_id', $_SESSION["kunde"]);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $vorname = $result["Vorname"];
    ?>
    <div class="user-info">
        <span>Hallo, <?php echo $vorname; ?></span><br>
        <button class="impressum" onclick="window.location.href='../PHP/bestellungen.php';">Meine Bestellungen</button><br>
        <button class="impressum" onclick="window.location.href='?logout=true';">Abmelden</button>  
    </div>
<?php endif; ?>

    <style>
    .user-info {
        color: black;
        padding-bottom: 5px;
        text-align: right; 
        font-size: 17px;
    }
    </style>

<div class="header">
    <img class="icon" src="../Bilder/icon.png"><h1>Pomodro Copyshop</h1>

    <div class="nav-links">
        <a href="../HTML/index.php">Startseite</a>
        <a href="../HTML/angebote.php">Angebote</a>
        <a href="../HTML/produkte.php">Produkte</a>
        <a href="../HTML/contact.php">Kontakt</a>    
    </div>

    <div class="icon-links">
        <a href="../PHP/login.php" class="icon-link">
            <img src="../Bilder/konto.png" alt="Mein Konto">
        </a>
        <a href="../HTML/warenkorb.php" class="icon-link">
            <img src="../Bilder/shop.png" alt="Warenkorb">
        </a>
    </div>
</div>