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
                <li class="current"><a href="prenotazione.php">Prenotazione</a></li>
                <li><a href="visualizza.php">Visualizza</a></li>
                <li class="current"><a href="monitoraggio_impianti.php">Monitoraggio impianti</a></li>
                <li><a href="modifica_utente.php">Info utente</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
    <div class='prenotazione'>
<?php
    session_start();
    if(!isset($_SESSION['pass'])) {
        header("Location: error.html");
        exit();
    } else {
        $codice_fiscale = $_SESSION['codice_fiscale'];
        $impianto = $_GET['impianto'];
        $_SESSION['impianto'] = $impianto;
        $database = mysql_connect("localhost", "root", "")
            or die("Errore di connessione al database");
    
        mysql_select_db("esame2")
            or die ("Errore connessione con database");
        
        $testo = "SELECT S.data_attivazione AS data_attivazione, S.data_scadenza AS data_scadenza
        FROM skipass S, utente U
        WHERE S.id_utente = U.id_utente AND U.codice_fiscale = '$codice_fiscale'";
        $query = mysql_query($testo);
        $righe = mysql_fetch_array($query);
        $data_attivazione = $righe['data_attivazione'];
        $data_scadenza = $righe['data_scadenza'];
        $testo = "SELECT I.orario_apertura AS ora_apertura, I.orario_chiusura AS ora_chiusura
        FROM impianto_risalita I
        WHERE I.nome = '$impianto'";
        $query = mysql_query($testo);
        $righe = mysql_fetch_array($query);
        $ora_apertura = $righe['ora_apertura'];
        $ora_chiusura = $righe['ora_chiusura'];
        $_SESSION['ora_apertura'] = $ora_apertura;
        $_SESSION['ora_chiusura'] = $ora_chiusura;
        echo "
        <form method='POST' action='sessionePrenotazione.php'>
        <h2>Scegli una data ed un orario</h2>
        <label for='data'>Data</label>
        <input type='date' name='data' min='$data_attivazione' max='$data_scadenza' required>
        <h3>Scegliere un orario tra $ora_apertura e $ora_chiusura sapendo che la risalita parte ogni 15 minuti</h3>
        <label for='ora'>Orario</label>
        <input type='time' name='orario' min='$ora_apertura' max='$ora_chiusura' required>
        <input type='submit' name='invia' value='Prenota'>
        </form>
        ";
    }
?>
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