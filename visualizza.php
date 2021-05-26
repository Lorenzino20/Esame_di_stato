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
                <li class="current"><a href="visualizza.php">Visualizza</a></li>
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
        $codice_fiscale = $_SESSION['codice_fiscale'];
        $database = mysql_connect("localhost", "root", "")
        or die("Errore di connessione al database");

        mysql_select_db("esame2")
        or die ("Errore connessione con database");

        $testo = "SELECT * FROM skipass S WHERE S.id_utente = (SELECT U.id_utente FROM utente U WHERE U.codice_fiscale = '$codice_fiscale')";
        $query = mysql_query($testo);
        $righe = mysql_num_rows($query);
        if($righe <> 0) {
            $testo = "SELECT I.nome AS nome, P.orario AS orario, P.id_risalita AS id_impianto, P.id_tessera AS id_tessera, P.data AS data 
            FROM prenota P, impianto_risalita I, skipass S, utente U 
            WHERE I.id_risalita = P.id_risalita AND P.id_tessera = S.id_tessera AND S.id_utente = U.id_utente 
            AND U.codice_fiscale = '$codice_fiscale'";
            $query = mysql_query($testo);
            $righe = mysql_num_rows($query);
    
            if($righe == 0) {
                echo "<div class='comment'><h1>Non ci sono prenotazioni associate a questo skipass. Se vuoi prenotare una risalita clicca <a href='prenotazione.php'>qui</a></h1></div>";
            } else {
                echo "<div class='tabella_prenotazioni'>
                <table class='prenotazioni'><tr><td>Visualizzazione prenotazioni</td></tr>";
                while($righe = mysql_fetch_array($query)) {
                    $risalita = $righe['id_impianto'];
                    $orario = $righe['orario'];
                    $id_tessera = $righe['id_tessera'];
                    $data = $righe['data'];
                    $pass = true;
                    echo "<tr>
                    <td>$righe[nome]</td>
                    <td>$righe[orario]</td>
                    <td>$righe[data]</td>
                    <td><a onclick=\"return confirm('Cancellare questo record?')\" href=\"cancella.php?id_risalita=".$risalita."&orario=".$orario."&id_tessera=".$id_tessera."&pass=".$pass."\">Cancella</a></td>
                    <td><a href=\"qrcode.php?id_risalita=".$risalita."&orario=".$orario."&id_tessera=".$id_tessera."\">Visualizza qrcode</a></td>
                    <td><a href='stampa_prenotazioni.php'>Stampa</a></td>
                    </tr>";
                }
                echo "</table></div>";
            }
        } else {
            echo "<div class='comment'><h2>Impossibile effettuare prenotazioni senza aver acquistato uno skipass. Per acquistarlo clicca <a href='acquistoSkipass.php'>qui</a></h2></div>";
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