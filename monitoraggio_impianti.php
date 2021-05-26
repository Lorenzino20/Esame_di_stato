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
            $testo = "SELECT S.id_tessera AS id_tessera 
                FROM skipass S, utente U 
                WHERE S.id_utente = U.id_utente AND U.codice_fiscale = '$codice_fiscale'";
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
                echo "<div class='legenda'>
            <h2 class='rosso'>Colore rosso indica prenotazioni di altri utenti.</h2>
            <h2 class='verde'>Colore verde indica prenotazioni personali.</h2> 
            </div>";
                echo "<div class='tabella_prenotazioni'>
                <table class='prenotazioni'><tr><td>Tabella impianti già prenotati</td></tr>
                <tr>
                <td>Nome impianto</td>
                <td>Descrizione impianto</td>
                <td>Data prenotazione</td>
                <td>Orario</td>
                <td>Posti totali</td>
                <td>Orario apertura</td>
                <td>Orario chiusura</td>
                <td>Posti</td>
                <td>Link</td>
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
                    <td>$righe[orario_chiusura]</td>";

                    $nome_impianto = $righe['nome_impianto'];
                    $testo = "SELECT COUNT(P.id_risalita) AS posti_occupati
                    FROM prenota P
                    WHERE P.id_risalita = (SELECT I.id_risalita
                    FROM impianto_risalita I
                    WHERE I.nome = '$nome_impianto') AND P.orario = '$righe[orario_prenotazione]' AND P.data = '$righe[data_prenotazione]'";
                    $qy = mysql_query($testo);
                    $ri = mysql_fetch_array($qy);
                    $posti_occupati = $ri['posti_occupati'];
                    $orario_query = $righe['orario_prenotazione'];
                    $data = $righe['data_prenotazione'];

                    $testo = "SELECT I.posti_totali AS posti_totali
                    FROM impianto_risalita I
                    WHERE I.nome = '$nome_impianto'";
                    $qy = mysql_query($testo);
                    $ri = mysql_fetch_array($qy);
                    $posti_totali = $ri['posti_totali'];
                    if($posti_totali == $posti_occupati) {
                        echo "<td>Posti conclusi</td>"; 
                    } else {
                        $posti_disponibili = $posti_totali-$posti_occupati;
                        echo "<td>Posti disponibili: $posti_disponibili</td>";
                        echo "<td><a href=\"prenotazione_impianto.php?impianto=".$nome_impianto."&data=".$data."&orario=".$orario_query."\">Prenota</a></td>";
                    }
                    echo "</tr>";
                }
                echo "</table></div>";
            } else {
                echo "<div class='comment'><h2>In questa giornata non ci sono prenotazioni in nessun impianto. Per prenotare clicca <a href='prenotazione.php'>qui<a/></h2></div>";
            }

        } else {
            $testo = "SELECT S.data_attivazione, S.data_scadenza 
            FROM skipass S 
            WHERE S.id_utente = (SELECT U.id_utente 
            FROM utente U 
            WHERE U.codice_fiscale = '$_SESSION[codice_fiscale]')";
            $query = mysql_query($testo);
            $riga =  mysql_num_rows($query);
            if($riga <> 0) {
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
                $numero_giorno_mese = date("j");
                $oggi_mese = date("n");
                echo "<div id='monitoraggio_title'><div class='comment'><h2>Visualizza gli impianti di risalita aperti durante i giorni in cui il tuo skipass è attivo.</h2></div></div>";
                echo "<div class='tabella_calendario'>
                <table class='calendario'>";
                for ($i = $oggi_mese; $i <= $mese_scadenza; $i++){
                    echo "<tr>";
                    if($i == $mese_attivazione) {
                        echo "<td>".date('F', mktime(0,0,0,$i+1,0,0))."</td>";
                        $num = cal_days_in_month(CAL_GREGORIAN, $i, date('Y'));
                        if($i < 10)
                            $i = "0".$i;
                        for($j = $numero_giorno_mese; $j <= $num; $j++){
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
                    echo "<div class='legenda'>
                    <h1>Legenda:</h1>
                    <h2 class='rosso'>Colore rosso indica prenotazioni di altri utenti.</h2>
                    <h2 class='verde'>Colore verde indica prenotazioni personali.</h2> 
                </div>";
                    echo "<div class='tabella_prenotazioni'>
                    <table class='prenotazioni'><tr><td>Tabella impianti già prenotati</td></tr>
                    <tr>
                    <td>Nome impianto</td>
                    <td>Descrizione impianto</td>
                    <td>Data prenotazione</td>
                    <td>Orario</td>
                    <td>Posti totali</td>
                    <td>Orario apertura</td>
                    <td>Orario chiusura</td>
                    <td>Posti</td>
                    <td>Link</td>
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
                        <td>$riga[orario_chiusura]</td>";

                        $nome_impianto = $riga['nome_impianto'];
                        $orario_query = $riga['orario_prenotazione'];
                        $data = $riga['data_prenotazione'];

                        $testo = "SELECT COUNT(P.id_risalita) AS posti_occupati
                        FROM prenota P
                        WHERE P.id_risalita = (SELECT I.id_risalita
                        FROM impianto_risalita I
                        WHERE I.nome = '$nome_impianto') AND P.orario = '$riga[orario_prenotazione]' AND P.data = '$riga[data_prenotazione]'";
                        $q = mysql_query($testo);
                        $row = mysql_fetch_array($q);
                        $posti_occupati = $row['posti_occupati'];
                        

                        $testo = "SELECT I.posti_totali AS posti_totali
                        FROM impianto_risalita I
                        WHERE I.nome = '$nome_impianto'";
                        $q = mysql_query($testo);
                        $row = mysql_fetch_array($q);
                        $posti_totali = $row['posti_totali'];
                        if($posti_totali == $posti_occupati) {
                            echo "<td>Posti conclusi</td>"; 
                        } else {
                            $posti_disponibili = $posti_totali-$posti_occupati;
                            echo "<td>Posti disponibili: $posti_disponibili</td>";
                            echo "<td><a href=\"prenotazione_impianto.php?impianto=".$nome_impianto."&data=".$data."&orario=".$orario_query."\">Prenota</a></td>";
                        }
                        echo "</tr>";
                    }
                    echo "</table></div>";
                } else {
                    echo "<div class='comment'><h2>Non ci sono prenotazioni. I posti sono tutti liberi.</h2></div>";
                }
            } else {
                echo"<div class='comment'><h2>Non c'è uno skipass associato. Per acquistarlo clicca <a href='acquistoSkipass.php'>qui</a></h2></div>";
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