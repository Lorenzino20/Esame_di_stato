<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Area Clienti</title>
</head>
<body>
    <div class="header">
        <div class="logo">
            <a href="index.html"><img src="Logo/logo_small.png"></a>
        </div>
        <div class="menu">
            <ul class="menu-items">
                <li><a href="utente.php">Area Clienti</a></li>
                <li><a href="prenotazione.php">Prenotazione</a></li>
                <li><a href="visualizza.php">Visualizza</a></li>
                <li><a href="monitoraggio_impianti.php">Monitoraggio impianti</a></li>
                <li class="current">Qrcode</li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
    <div class="qrcode">
<?php
    session_start();
    if(!isset($_SESSION['pass'])) {
        header("Location: error.html");
        exit();
    } else {
        if(isset($_SESSION['numero_tessera'])) {
            $id_tessera = $_SESSION['id_tessera'];
            $id_risalita = $_SESSION['id_risalita'];
            $orario = $_SESSION['orario'];
        } else {
            $id_tessera = $_GET['id_tessera'];
            $id_risalita = $_GET['id_risalita'];
            $orario = $_GET['orario'];
        }
        
        $database = mysql_connect("localhost", "root", "")
            or die("Errore di connessione al database");
    
        mysql_select_db("esame2")
            or die ("Errore connessione con database");

        $testo = "SELECT * FROM prenota WHERE id_tessera = '$id_tessera'";

        $query = mysql_query($testo);
        $righe = mysql_num_rows($query);

        if($righe <> 0) {
            $vettore = mysql_fetch_array($query);
            $tessera = $vettore['id_tessera'];
            $qrcode = "https://chart.apis.google.com/chart?cht=qr&chs=150x150&chl=id_tessera:".$id_tessera."id_risalita:".$id_risalita."orario:".$orario;
            echo "<h1>Codice generato</h1>";
            echo "<h2>Utilizza questo codice per usufruire della risalita prenotata</h2>";
            echo "<img src='$qrcode'></img>";
            exit();
        } else {
            $oggi = date ("Y/m/d");
            $due_mesi = strtotime('+60 day', strtotime($oggi));
            $due_mesi = date('Y/m/d', $due_mesi);
            $testo = "INSERT INTO skipass(risalite_rimanenti, data_attivazione, data_scadenza, id_utente) VALUES ('$p', '$oggi', '$due_mesi', '$id_utente')";
            $query = mysql_query($testo);
            if($query) {  
                $testo = "SELECT * FROM prenota WHERE id_tessera = '$id_tessera'";
                $query = mysql_query($testo);
                $vettore = mysql_fetch_array($query);
                $tessera = $vettore['id_tessera'];
                $qrcode = "https://chart.apis.google.com/chart?cht=qr&chs=150x150&chl=id_tessera".$id_tessera."id_risalita:".$id_risalita."orario:".$orario;
                echo "<h1>Skipass generato</h1>";
                echo "<img src='$qrcode'></img>";
            }
            else {
                echo "<h1>Skipass non generato</h1>";     
            } 
        }
        mysql_close();
    }
?>
</div>
</div>
<div class="footer">
        <span>Sito realizzato da Lorenzo D'Amico</span>
        <span>Copyright©2020 LDA SpA</span>
        <span>Tutti i diritti riservati</span>
        <i class="fab fa-facebook-f"></i>
        <i class="fab fa-instagram"></i>
    </div>
</body>
</html>