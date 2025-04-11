<?php
include('../PHP/header.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrierung</title>

    <link rel="icon" href="../Bilder/icon.png" type="image/png">
    <link rel="stylesheet" href="../CSS/style.css">
    <link href="https://fonts.googleapis.com/css2?family=DynaPuff:wght@400..700&display=swap" rel="stylesheet">

</head>
<body>
    
    <div class="text-container">
        <h2>Registrierung</h2>     
    </div>

        <div class="contact-container">
            
            <form method="POST">
                <div class="form-group">
                    <label for="Vorname">Vorname:</label>
                    <input type="text" name="Vorname" required>
                </div>
                <div class="form-group">
                    <label for="Nachname">Nachname:</label>
                    <input type="text" name="Nachname" required>
                </div>
                <div class="form-group">
                    <label for="Email">E-Mail:</label>
                    <input type="email" name="Email" required>
                </div>
                <div class="form-group">
                    <label for="pw">Passwort:</label>
                    <input type="password" name="pw" required>
                </div>
                <button type="submit" class="styled-button">Registrieren</button>
            </form>
        </div>

        <?php
        if(isset($_POST['Nachname'])){

            $servername = "localhost";
            $username = "copyshop";
            $password = "cppw";
            $dbname = "copyshop";
            try {
                $conn = new PDO("mysql:host=$servername;dbname=copyshop", $username,$password);
                // set the PDO error mode to exception
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                //echo "Connected successfully";
                //return $conn;
            } catch(PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            }


            //$kundstr = array('Nachname'=>$_POST['Nachname'], 'Vorname'=> $_POST['Vorname'],'Email'=> $_POST['Email'],'hash'=>$_POST['pw']);
            //addkunde($kundstr,);
            $hash = password_hash($_POST['pw'],PASSWORD_BCRYPT);

            $stmt = $conn->prepare("INSERT INTO kunden (Nachname, Vorname, Email, pwhash) VALUES (:Nn, :Vn, :Em, :hsh)");

            $stmt->bindParam(':Nn', $_POST['Nachname']);
            $stmt->bindParam(':Vn', $_POST['Vorname']);
            $stmt->bindParam(':Em', $_POST['Email']);
            $stmt->bindParam(':hsh', $hash);

            if ($stmt->execute()) {
                $_SESSION['kunde'] = $conn->lastInsertId(); 
                $_SESSION['vorname'] = $_POST['Vorname']; 
        
                header("Location: login.php");
                exit();
            }
            $conn = null;
        }

        
        ?>
    
        <div class="footer">
            
        <p class="footer">© <span id="year"></span> Pomodro Copyshop. Alle Rechte vorbehalten. <button class="impressum" onclick="window.location.href='../HTML/impressum.php';">Impressum</button></p>
        
        <script>
            document.getElementById("year").textContent = new Date().getFullYear();
        </script>
    
    </div>

</body>
</html>