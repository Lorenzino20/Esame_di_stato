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
        $database = mysql_connect("localhost", "root", "")
        or die("Errore di connessione al database");

        mysql_select_db("esame2")
        or die ("Errore connessione con database");

        if(isset($_GET['data'])) {
            $codice_fiscale = $_SESSION['codice_fiscale'];
            $testo = "SELECT S.id_tessera AS id_tessera 
                FROM skipass S, utente U 
                WHERE S.id_utente = U.id_utente AND U.codice_fiscale = 'DMCMNL23E45T456T'";
            $query = mysql_query($testo);
            $righe = mysql_fetch_array($query);
            $id_tessera = $righe['id_tessera'];
            $data = $_GET['data'];
            $testo= "SELECT P.id_tessera AS id_tessera, P.orario AS orario_prenotazione, P.data AS data_prenotazione, I.nome AS nome_impianto, 
            I.descrizione AS descrizione_impianto, I.posti_totali AS posti_totali,
            I.orario_apertura AS orario_apertura, I.orario_chiusura AS orario_chiusura  
            FROM prenota P, impianto_risalita I 
            WHERE P.id_risalita = I.id_risalita AND P.data = '$data'";
            $query = mysql_query($testo);
            $riga = mysql_num_rows($query);
            if($riga <> 0) {
                echo "<div class='tabella_utenti'>
                <table class='utenti'><tr><td>Tabella impianti già prenotati</td></tr>
                <tr>
                <td>Nome impianto</td>
                <td>Descrizione impianto</td>
                <td>Data prenotazione</td>
                <td>Orario</td>
                <td>Posti totali</td>
                <td>Orario apertura</td>
                <td>Orario chiusura</td>
                </tr>
                ";
                while($righe = mysql_fetch_array($query)) {
                    if($righe['id_tessera'] == $id_tessera)
                    echo "<tr class='matched'>";
                    else 
                    echo "<tr class='not-matched'>";
                    echo "
                    <td>$righe[nome_impianto]</td>
                    <td>$righe[descrizione_impianto]</td>
                    <td>$righe[data_prenotazione]</td>
                    <td>$righe[orario_prenotazione]</td>
                    <td>$righe[posti_totali]</td>
                    <td>$righe[orario_apertura]</td>
                    <td>$righe[orario_chiusura]</td>
                    </tr>";
                }
                echo "</table></div>";
                $testo = "SELECT "; /* Visualizza l'orario più vicino a questo già prenotato */
            } else {
                echo "<h2>In questa giornata non ci sono prenotazioni in nessun impianto. Per prenotare clicca <a href='prenotazione.php'>qui<a/></h2>";
            }

        } else {
            $testo = "SELECT S.data_attivazione, S.data_scadenza 
            FROM skipass S 
            WHERE S.id_utente = (SELECT U.id_utente 
            FROM utente U 
            WHERE U.codice_fiscale = '$_SESSION[codice_fiscale]')";
            $query = mysql_query($testo);
            $righe = mysql_fetch_array($query);
            $data_attivazione = $righe['data_attivazione'];
            $data_scadenza = $righe['data_scadenza'];
    
            $attivazione = explode('-',$data_attivazione);
            $giorno_attivazione = $attivazione[2];
            $mese_attivazione = $attivazione[1];
            $scadenza = explode('-',$data_scadenza);
            $giorno_scadenza = $scadenza[2];
            $mese_scadenza = $scadenza[1];
            $anno = $attivazione[0];
            echo "<h3>Visualizza gli impianti di risalita aperti durante i giorni in cui il tuo skipass è attivo.</h3>";
            echo "<div class='calendario'><table class='tabella_calendario'>";
            for ($i = $mese_attivazione; $i <= $mese_scadenza; $i++){
                echo "<tr>";
                if($i == $mese_attivazione) {
                    echo "<td>".date('F', mktime(0,0,0,$i+1,0,0))."</td>";
                    $num = cal_days_in_month(CAL_GREGORIAN, $i, date('Y'));
            
                    for($j = $giorno_attivazione; $j <= $num; $j++){
                        echo "<td><a href=\"monitoraggio_impianti.php?data=".$anno.$i.$j."\">$j</a></td>";
                    }
                    echo "</tr>";
                } else if($i == $mese_scadenza) {
                    echo "<td>".date('F', mktime(0,0,0,$i+1,0,0))."</td>";
                    $num = cal_days_in_month(CAL_GREGORIAN, $i, date('Y'));
            
                    for($j = 1; $j <= $giorno_scadenza; $j++){
                        echo "<td><a href=\"monitoraggio_impianti.php?data=".$anno.$i.$j."\">$j</a></td>";
                    }
                    echo "</tr>";
                } else {
                    echo "<td>".date('F', mktime(0,0,0,$i+1,0,0))."</td>";
                    $num = cal_days_in_month(CAL_GREGORIAN, $i, date('Y'));
            
                    for($j = 1; $j <= $num; $j++){
                        echo "<td><a href=\"monitoraggio_impianti.php?data=".$anno.$i.$j."\">$j</a></td>";
                    }
                    echo "</tr>";
                }
            }
            echo "</table></div>";
            $testo= "SELECT P.orario AS orario_prenotazione, P.data AS data_prenotazione, I.nome AS nome_impianto, 
            I.descrizione AS descrizione_impianto, I.posti_totali AS posti_totali,
            I.orario_apertura AS orario_apertura, I.orario_chiusura AS orario_chiusura  
            FROM prenota P, impianto_risalita I 
            WHERE P.id_risalita = I.id_risalita";
            $query = mysql_query($testo);
            $righe = mysql_fetch_array($query);
            if($righe['data_prenotazione'] <> NULL) {
                $codice_fiscale = $_SESSION['codice_fiscale'];
                $testo = "SELECT S.id_tessera AS id_tessera 
                FROM skipass S, utente U 
                WHERE S.id_utente = U.id_utente AND U.codice_fiscale = '$codice_fiscale'";
                $query = mysql_query($testo);
                $righe = mysql_fetch_array($query);
                $id_tessera = $righe['id_tessera'];
                $testo= "SELECT P.id_tessera AS id_tessera, P.orario AS orario_prenotazione, P.data AS data_prenotazione, 
                I.nome AS nome_impianto, I.descrizione AS descrizione_impianto, I.posti_totali AS posti_totali, 
                I.orario_apertura AS orario_apertura, I.orario_chiusura AS orario_chiusura 
                FROM prenota P, impianto_risalita I 
                WHERE P.id_risalita = I.id_risalita";
                $query = mysql_query($testo);
                echo "<div class='tabella_utenti'>
                <table class='utenti'><tr><td>Tabella impianti già prenotati</td></tr>
                <tr>
                <td>Nome impianto</td>
                <td>Descrizione impianto</td>
                <td>Data prenotazione</td>
                <td>Orario</td>
                <td>Posti totali</td>
                <td>Orario apertura</td>
                <td>Orario chiusura</td>
                </tr>
                ";
                while($riga = mysql_fetch_array($query)) {
                    if($riga['id_tessera'] == $id_tessera)
                    echo "<tr class='matched'>";
                    else 
                    echo"<tr class='not-matched'>";
                    echo "
                    <td>$riga[nome_impianto]</td>
                    <td>$riga[descrizione_impianto]</td>
                    <td>$riga[data_prenotazione]</td>
                    <td>$riga[orario_prenotazione]</td>
                    <td>$riga[posti_totali]</td>
                    <td>$riga[orario_apertura]</td>
                    <td>$riga[orario_chiusura]</td>
                    </tr>";
                }
                echo "</table></div>";
            } else {
                echo "<h2>Non ci sono prenotazioni. I posti sono tutti liberi.</h2>";
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