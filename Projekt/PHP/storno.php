<?php
session_start();

// Datenbankverbindung herstellen
$servername = "localhost";
$username = "copyshop";
$password = "cppw";
$dbname = "copyshop";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Überprüfen, ob das Formular gesendet wurde und Checkboxen ausgewählt wurden
if (isset($_POST['stornieren']) && is_array($_POST['stornieren'])) {
    $bestellnummern = $_POST['stornieren'];

    // Stornieren aller ausgewählten Bestellungen
    $stmt = $conn->prepare("DELETE FROM bestellungen WHERE Bestellnr = :bestellnr AND Kunden_id = :kunde_id");
    $stmt->bindParam(':kunde_id', $_SESSION["kunde"], PDO::PARAM_INT);
    
    foreach ($bestellnummern as $bestellnr) {
        $stmt->bindParam(':bestellnr', $bestellnr, PDO::PARAM_INT);
        $stmt->execute();
    }

    // Erfolgsnachricht anzeigen
    echo "<div style='text-align: center;'>";
    echo "<h2>Ihre Bestellung wurde erfolgreich storniert!</h2>";
    echo "<br><br>";
    echo "<button type='button' class='styled-button' onclick=\"window.location.href='bestellungen.php';\">Zurück zu den Bestellungen</button>";
    echo "</div>";
} else {
    // Fehlermeldung, falls keine Bestellung ausgewählt wurde
    echo "<div style='text-align: center;'>";
    echo "<h2>Keine Bestellung zum Stornieren ausgewählt!</h2>";
    echo "<br><br>";
    echo "<button type='button' class='styled-button' onclick=\"window.location.href='bestellungen.php';\">Zurück zu den Bestellungen</button>";
    echo "</div>";
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stornierung</title>
    
    <link rel="icon" href="../Bilder/icon.png" type="image/png">
    <link rel="stylesheet" href="../CSS/style.css">
    <link href="https://fonts.googleapis.com/css2?family=DynaPuff:wght@400..700&display=swap" rel="stylesheet">

</head>
<body>