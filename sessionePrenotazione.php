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
    <div class="area_cliente">
<?php
    session_start();
    if(!isset($_SESSION['pass'])) {
        header("Location: error.html");
        exit();
    } else {
        $data = $_POST['data'];
        $pista = $_SESSION['impianto'];
        $orario = $_POST['orario'];
        $ora_apertura = $_SESSION['ora_apertura'];
        $ora_chiusura = $_SESSION['ora_chiusura'];
        
        $database = mysql_connect("localhost", "root", "")
            or die("Errore di connessione al database");
    
        mysql_select_db("esame2")
            or die ("Errore connessione con database");

        $ora = explode(':', $orario);
        $ore_inserite = $ora[0];
        $minuti_inseriti = $ora[1];

        if($minuti_inseriti %15 <> 0)
            echo "<h2>Impossibile inserire questo tipo di orario. Riprova <a href='prenotazione.php'>qui</a></h2>";
        else {
            $testo = "SELECT COUNT(P.id_risalita) AS posti_occupati
            FROM prenota P
            WHERE P.id_risalita = (SELECT I.id_risalita
            FROM impianto_risalita I
            WHERE I.nome = '$pista') AND P.orario = '$orario' AND P.data = '$data'";
            $query = mysql_query($testo);
            $righe = mysql_fetch_array($query);
            $posti_occupati = $righe['posti_occupati'];

            $testo = "SELECT I.posti_totali AS posti_totali
            FROM impianto_risalita I
            WHERE I.nome = '$pista'";
            $query = mysql_query($testo);
            $righe = mysql_fetch_array($query);
            $posti_totali = $righe['posti_totali'];
    
            if($posti_totali <= $posti_occupati) {
                echo "<h2>Impossibile prenotare l'impianto di risalita per questo orario</h2>";
            } else {
                $codice_fiscale = $_SESSION['codice_fiscale'];
                $testo = "SELECT id_tessera, risalite_rimanenti
                FROM skipass, utente
                WHERE skipass.id_utente = utente.id_utente AND utente.codice_fiscale = '$codice_fiscale'";
                $query = mysql_query($testo);
                $righe = mysql_fetch_array($query);
                $id_tessera = $righe['id_tessera'];
                $risalite_rimanenti = $righe['risalite_rimanenti'];
                
                $testo = "SELECT id_risalita
                FROM impianto_risalita
                WHERE impianto_risalita.nome = '$pista'";
                $query = mysql_query($testo);
                $righe = mysql_fetch_array($query);
                $id_risalita = $righe['id_risalita'];

                $testo = "SELECT *
                FROM prenota P
                WHERE P.id_tessera = '$id_tessera' AND P.id_risalita = '$id_risalita' AND P.orario = '$orario' AND P.data = '$data'";
                $query = mysql_query($testo);
                $righe = mysql_num_rows($query);

                if($righe <> 0)
                    echo "<h2>Hai già prenotato la risalita. Per visualizzare il qrcode clicca <a href='visualizza.php'>qui</a></h2>";
                else {
                    $testo = "INSERT INTO prenota (id_tessera, id_risalita, orario, data) VALUES ('$id_tessera', '$id_risalita', '$orario', '$data')";
                    $query = mysql_query($testo);
                    $risalite = $risalite_rimanenti -1; 
                    $testo = "UPDATE skipass SET risalite_rimanenti= $risalite WHERE id_tessera = '$id_tessera'";
                    $query = mysql_query($testo);
                    if($query) {
                        echo "<h2>Prenotazione effettuata. Per visualizzare il qrcode clicca <a href=\"qrcode.php?id_risalita=".$id_risalita."&orario=".$orario."&id_tessera=".$id_tessera."\">qui</a> oppure recati nella sezione prenotazioni</h2>";
                    } else {
                        echo "<h2>Prenotazione non effettuata. Errore nella prenotazione clicca<a href='prenotazione.php'>qui</a> per riprovare</h2>";
                    }
                }   
            }
        }        
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