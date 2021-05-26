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
        $codice_fiscale = $_SESSION['codice_fiscale'];
        
        $database = mysql_connect("localhost", "root", "")
            or die("Errore di connessione al database");
    
        mysql_select_db("esame2")
            or die ("Errore connessione con database");
    
        $testo = "SELECT * FROM skipass S WHERE S.id_utente = (SELECT U.id_utente FROM utente U WHERE U.codice_fiscale = '$codice_fiscale')";
        $query = mysql_query($testo);
        $righe = mysql_num_rows($query);

        if($righe <> 0) {
            $testo = "SELECT S.risalite_rimanenti FROM skipass S WHERE S.id_utente = (SELECT U.id_utente FROM utente U WHERE U.codice_fiscale = '$codice_fiscale')";
            $query = mysql_query($testo);
            $righe = mysql_fetch_array($query);
            $risalite_rimanenti = $righe['risalite_rimanenti'];
            if($risalite_rimanenti > 0) {
                $testo = "SELECT I.nome AS nome, I.descrizione AS descrizione, I.posti_totali AS posti_totali
                FROM impianto_risalita I";
                $query = mysql_query($testo);
                echo"<div class='tabella_prenotazioni'>
                <table class='prenotazioni'>
                <th>Tabella impianti di risalita<th>
                <tr>
                <td>Nome</td>
                <td>Descrizione</td>
                <td>Posti totali</td>
                </tr>";
                while($vettore = mysql_fetch_array($query)) {
                    echo"<tr>
                    <td>$vettore[nome]</td>
                    <td>$vettore[descrizione]</td>
                    <td>$vettore[posti_totali]</td>
                    <td><a href=\"prenotazione_impianto.php?impianto=".$vettore['nome']."\">Scegli orario</a></td>";
                }
                echo "</table></div>";
            } else {
                echo "<div class='comment'><h2>Impossibile effettuare prenotazioni con uno skipass scaduto o senza risalite. Per acquistare un altro skipass clicca <a href='acquistoSkipass.php'>qui</a></h2></div>";
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