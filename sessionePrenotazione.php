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

        $ora_chiusura = explode(':', $ora_chiusura);
        $ore_chiusura = $ora_chiusura[0];
        $minuti_chiusura = $ora_chiusura[1];
        

        $ora_apertura = explode(':', $ora_apertura);
        $ore_apertura = $ora_apertura[0];
        $minuti_apertura = $ora_apertura[1];

        $numero_giorno_mese = date("j");
        $oggi_mese = date("n");
        $oggi_anno = date("y");

        $data_oggi = date("Y/m/g");
        $ora_adesso = date("H:i");

        if($data == $data_oggi) {
            if($orario < $ora_adesso) {
                echo "<div class='comment'><h2>Impossibile prenotare per un orario passato. L'orario deve essere tra quello di apertura e quello di chiusura. Per riprovare clicca <a href='prenotazione.php'>qui</a><h2></div>";
                exit();
            }
        }
        if($minuti_inseriti %15 <> 0)
            echo "<div class='comment'><h2>Impossibile inserire questo tipo di orario. Riprova <a href='prenotazione.php'>qui</a></h2></div>";
        else {
            if($ore_inserite > $ore_chiusura) {
                echo "<div class='comment'><h2>Impossibile inserire questo tipo di orario. Riprova <a href='prenotazione.php'>qui</a></h2></div>";
                exit();
            } else if($ore_inserite == $ore_chiusura && $minuti_inseriti > $minuti_chiusura) {
                echo "<div class='comment'><h2>Impossibile inserire questo tipo di orario. Riprova <a href='prenotazione.php'>qui</a></h2></div>";
                exit();
            }
            if($ore_inserite < $ore_apertura) {
                echo "<div class='comment'><h2>Impossibile inserire questo tipo di orario. Riprova <a href='prenotazione.php'>qui</a></h2></div>";
                exit();
            } else if($ore_inserite == $ore_apertura && $minuti_inseriti < $minuti_apertura) {
                echo "<div class='comment'><h2>Impossibile inserire questo tipo di orario. Riprova <a href='prenotazione.php'>qui</a></h2></div>";
                exit();
            }
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
    
            if($posti_totali == $posti_occupati) {
                echo "<div class='comment'><h2>Impossibile prenotare l'impianto di risalita per questo orario. Già sono stati prenotati tutti i posti.</h2></div>";
                $ora_c = explode(':', $ora_chiusura);
                $ore_chiusura = $ora_c[0];
                $minuti_chiusura = $ora_c[1];
                echo "<div class='comment'><h2>I prossimi posti disponibili sono:</h2></div>";
                while($ore_inserite <> $ore_chiusura || $minuti_inseriti <> $minuti_chiusura) {
                    if($minuti_inseriti == 45) {
                        $minuti_inseriti = '00';
                        $ore_inserite = $ore_inserite + 1;
                    }
                    else  
                    $minuti_inseriti = $minuti_inseriti + 15;
                    $orario_query = $ore_inserite.':'.$minuti_inseriti;
                    $testo = "SELECT COUNT(P.id_risalita) AS posti_occupati
                    FROM prenota P
                    WHERE P.id_risalita = (SELECT I.id_risalita
                    FROM impianto_risalita I
                    WHERE I.nome = '$pista') AND P.orario = '$orario_query' AND P.data = '$data'";
                    $query = mysql_query($testo);
                    $righe = mysql_fetch_array($query);
                    $posti_occupati = $righe['posti_occupati'];
                    echo "<h3>$orario_query--> Posti occupati:$posti_occupati</h3>";
                    echo "<h3><a href=\"prenotazione_impianto.php?impianto=".$pista."&data=".$data."&orario=".$orario_query."\">Prenota</a></h3>";
                    echo "<br/>";
                }     
            } else {
                $codice_fiscale = $_SESSION['codice_fiscale'];
                $testo = "SELECT id_tessera, risalite_rimanenti
                FROM skipass, utente
                WHERE skipass.id_utente = utente.id_utente AND utente.codice_fiscale = '$codice_fiscale'";
                $query = mysql_query($testo);
                $righe = mysql_fetch_array($query);
                $id_tessera = $righe['id_tessera'];
                $risalite_rimanenti = $righe['risalite_rimanenti'];

                if($risalite_rimanenti == 0 || $risalite_rimanenti < 0) {
                    echo "<div class='comment'><h2>Impossibile effettuare la prenotazione. Sono concluse le risalite prenotabili</h2></div>";
                }
                
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

                if($righe <> 0) {
                    echo "<div class='comment'><h2>Hai già prenotato la risalita. Per visualizzare il qrcode clicca <a href='visualizza.php'>qui</a></h2></div>";
                    $ora_c = explode(':', $ora_chiusura);
                    $ore_chiusura = $ora_c[0];
                    $minuti_chiusura = $ora_c[1];
                
                    if($ore_inserite == $ore_chiusura) {
                        echo "<div class='comment'><h2>Non ci sono orari disponibili dopo quello inserito.</h2></div>";
                    } else {
                        echo "<div class='comment'><h2>I prossimi posti disponibili sono:</h2></div>";
                        while($ore_inserite <> $ore_chiusura || $minuti_inseriti <> $minuti_chiusura) {
                            if($minuti_inseriti == 45) {
                                $minuti_inseriti = '00';
                                $ore_inserite = $ore_inserite + 1;
                            }
                            else {
                                $minuti_inseriti = $minuti_inseriti + 15;
                                $orario_query = $ore_inserite.':'.$minuti_inseriti;
                                $testo = "SELECT COUNT(P.id_risalita) AS posti_occupati
                                FROM prenota P
                                WHERE P.id_risalita = (SELECT I.id_risalita
                                FROM impianto_risalita I
                                WHERE I.nome = '$pista') AND P.orario = '$orario_query' AND P.data = '$data'";
                                $query = mysql_query($testo);
                                $righe = mysql_fetch_array($query);
                                $posti_occupati = $righe['posti_occupati'];
                                echo "<h3>$orario_query--> Posti occupati:$posti_occupati</h3>";
                                echo "<h3><a href=\"prenotazione_impianto.php?impianto=".$pista."&data=".$data."&orario=".$orario_query."\">Prenota</a></h3>";
                                echo "<br/>";
                            }
                        }  
                    }       
                } else {
                    $testo = "INSERT INTO prenota (id_tessera, id_risalita, orario, data) VALUES ('$id_tessera', '$id_risalita', '$orario', '$data')";
                    $query = mysql_query($testo);
                    $risalite = $risalite_rimanenti -1; 
                    $testo = "UPDATE skipass SET risalite_rimanenti= $risalite WHERE id_tessera = '$id_tessera'";
                    $query = mysql_query($testo);
                    if($query) {
                        echo "<div class='comment'><h2>Prenotazione effettuata. Per visualizzare il qrcode clicca <a href=\"qrcode.php?id_risalita=".$id_risalita."&orario=".$orario."&id_tessera=".$id_tessera."\">qui</a> oppure recati nella sezione prenotazioni</h2></div>";
                    } else {
                        echo "<div class='comment'><h2>Prenotazione non effettuata. Errore nella prenotazione clicca<a href='prenotazione.php'>qui</a> per riprovare</h2></div>";
                    }
                }   
            }
        }
        mysql_close();   
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