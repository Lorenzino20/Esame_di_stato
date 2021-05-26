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
                <li><a href="monitoraggio_impianti.php">Monitoraggio impianti</a></li>
                <li><a href="modifica_utente.php">Info utente</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
    <div class='area_cliente'>
        <div class="monitoraggio">
<?php
    session_start();
    if(!isset($_SESSION['pass'])) {
        header("Location: error.html");
        exit();
    } else {
        $database = mysql_connect("localhost", "root", "")
            or die("Errore di connessione al database");
            
        mysql_select_db("esame2")
            or die ("Errore connessione con database");

        if(isset($_GET['data'])) {
            $codice_fiscale = $_SESSION['codice_fiscale'];
            $testo = "SELECT S.risalite_rimanenti AS risalite_rimanenti
            FROM skipass S, utente U
            WHERE S.id_utente = U.id_utente AND U.codice_fiscale = '$codice_fiscale'";
            $query = mysql_query($testo);
            $righe = mysql_fetch_array($query);
            $risalite_rimamenti = $righe['risalite_rimanenti'];
            if($risalite_rimamenti < 0 || $risalite_rimamenti == 0) {
                echo "<h2>Impossibile effettuare una prenotazione</h2>";
            } else {
                $data = $_GET['data'];
                $orario = $_GET['orario'];
                $impianto = $_GET['impianto'];
                $_SESSION['impianto'] = $impianto;
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
                $numero_giorno_mese = date("j");
                $oggi_mese = date("n");
                $oggi_anno = date("Y");
                $data_oggi = $oggi_anno."-0".$oggi_mese."-".$numero_giorno_mese;
                $ora_adesso = date("H:i");
                if($ora_adesso > $ora_chiusura) {
                    echo "<h2>Impossibile prenotare. L'impianto è chiuso</h2>";
                } else {
                    echo "<div class='prenotazione'>
                    <form method='POST' action='sessionePrenotazione.php'>
                    <h2>Scegli una data ed un orario</h2>
                    <label for='data'>Data</label>
                    <input type='date' name='data' min='$data_oggi' max='$data_scadenza' value='$data' required>
                    <h2>Scegliere un orario tra $ora_adesso e $ora_chiusura sapendo che la risalita parte ogni 15 minuti</h2>
                    <label for='ora'>Orario</label>
                    <input type='time' name='orario' min='$ora_adesso' max='$ora_chiusura' value='$orario' required>
                    <input type='submit' name='invia' value='Prenota'>
                    </form>
                    </div>";
                }
                
            } 
        } else {
            $codice_fiscale = $_SESSION['codice_fiscale'];
            $testo = "SELECT S.risalite_rimanenti AS risalite_rimanenti
            FROM skipass S, utente U
            WHERE S.id_utente = U.id_utente AND U.codice_fiscale = '$codice_fiscale'";
            $query = mysql_query($testo);
            $righe = mysql_fetch_array($query);
            $risalite_rimamenti = $righe['risalite_rimanenti'];
            if($risalite_rimamenti < 0 || $risalite_rimamenti == 0) {
                echo "<h2>Impossibile effettuare una prenotazione</h2>";
            } else {
                $impianto = $_GET['impianto'];
                $_SESSION['impianto'] = $impianto;
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
                $numero_giorno_mese = date("j");
                $oggi_mese = date("n");
                $oggi_anno = date("Y");
                $data_oggi = $oggi_anno."-0".$oggi_mese."-".$numero_giorno_mese;
                $ora_adesso = date("H:i");
                print_r($_SESSION);
                echo "<div class='prenotazione'>
                <form method='POST' action='sessionePrenotazione.php'>
                <h2>Scegli una data ed un orario</h2>
                <label for='data'>Data</label>
                <input type='date' name='data' min='$data_oggi' max='$data_scadenza' placeholder='aaaa/mm/gg' required>
                <h2>Scegliere un orario tra $ora_apertura e $ora_chiusura sapendo che la risalita parte ogni 15 minuti</h2>
                <label for='ora'>Orario</label>
                <input type='time' name='orario' min='$ora_apertura' max='$ora_chiusura' placeholder='10:00' required>
                <input type='submit' name='invia' value='Prenota'>
                </form></div>
                "; 
            }
        }
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