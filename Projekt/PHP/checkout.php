<?php
session_start();
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vielen Dank!</title>
    
    <link rel="icon" href="../Bilder/icon.png" type="image/png">
    <link rel="stylesheet" href="../CSS/style.css">
    <link href="https://fonts.googleapis.com/css2?family=DynaPuff:wght@400..700&display=swap" rel="stylesheet">

</head>
<body>

<?php
if(!isset($_SESSION["kunde"])){
    echo "<button type='button' class='styled-button' onclick=\"window.location.href='login.php';\" style='display: block; margin: 0 auto; text-align: center;'>Bitte zuerst anmelden</button>";
}else{
    // Datenbankverbindung herstellen
    $servername = "localhost";
    $username = "copyshop";
    $password = "cppw";
    $dbname = "copyshop";

    try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();

    }
    

    $kid = $_SESSION['kunde'];
    //var_dump($_SESSION["cart"]);
    $datum = date('d.m.Y');
    $stmt_b = $conn->prepare('INSERT INTO bestellungen (Kunden_id, Gesamtpreis, Datum) Values (:k, :p, :d)');
    $stmt_b->bindParam('k', $kid);
    $stmt_b->bindParam('p', $_SESSION['total']);
    $stmt_b->bindParam('d', $datum);
    $stmt_b->execute();
    $abfr = $conn->prepare("SELECT LAST_INSERT_ID()");
    $abfr->execute();
    $bstnr = $abfr->fetchAll(PDO::FETCH_NUM)[0][0];

    foreach($_SESSION["cart"] as $k => $v){ 

        $stmt=$conn->prepare("INSERT INTO bestellposten(Bestellnr,Produkt, Format, Preis, Menge, Speicherort) VALUES(:b,:p,:f, :pr, :m, :s)");
        $stmt->bindParam("b", $bstnr);
        $stmt->bindParam("p", $v['product']);
        $stmt->bindParam('f', $v['option']);
        $stmt->bindParam('pr', $v['price']);
        $stmt->bindParam('m', $v['quantity']);
        $stmt->bindParam('s', $v['image']);
        $stmt->execute();

    }
    $_SESSION["cart"] = [];

    echo "<div style='text-align: center;'>";
    echo "<h2>Vielen Dank für Ihre Bestellung!</h2>";
    echo "<p>Ihre Bestellung wurde erfolgreich übermittelt.</p>";
    echo "<p>Ihre Bestellnummer ist: ".$bstnr."</p>";
    echo "<br><br>"; 
    
    echo "<button type='button' class='styled-button' onclick=\"window.location.href='../HTML/index.php';\">Zurück zur Startseite</button>";
    echo "</div>";
    }

    ?>

        
 
</body>
</html>    

