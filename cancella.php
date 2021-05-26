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
                <li class="current"><a href="utente.php">Area Clienti</a></li>
                <li><a href="prenotazione.php">Prenotazione</a></li>
                <li><a href="visualizza.php">Visualizza</a></li>
                <li><a href="monitoraggio_impianti.php">Monitoraggio impianti</a></li>
                <li><a href="modifica_utente.php">Info utente</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
    <div class="area_cliente">
<?php
    if(!isset($_GET['pass'])) {
        header("Location: error.html");
        exit();
    } else {
        header('Content-type: text/html; charset=utf-8');

        $id_r = $_GET['id_risalita'];
        $id_o = $_GET['orario'];
        $id_n = $_GET['id_tessera'];
    
        $database = mysql_connect("localhost", "root", "")
            or die ("Errore connessione server");
    
        mysql_select_db("esame2") 
            or die ("Errore connessione database");
        
        $testo = "SELECT * FROM prenota WHERE id_risalita = '$id_r' AND orario = '$id_o' AND id_tessera = '$id_n'";
        $query = mysql_query($testo);
        $righe = mysql_num_rows($query);
        if($righe <> 0) {
            $testo = "delete FROM prenota where id_risalita='$id_r' AND orario='$id_o' AND id_tessera='$id_n'";
            $query = mysql_query($testo);
            $testo = "SELECT S.risalite_rimanenti AS risalite
            FROM skipass S
            WHERE s.id_tessera = '$id_n'";
            $query = mysql_query($testo);
            $righe = mysql_fetch_array($query);
            $risalite = $righe['risalite'] + 1;
        
            $testo = "UPDATE skipass SET risalite_rimanenti = '$risalite' WHERE id_tessera = '$id_n'";
            $query = mysql_query($testo);
        
            if($query) {
                echo "<h1>Eliminazione effettuata. Per visualizzare le prenotazioni rimanenti clicca <a href='visualizza.php'>qui</a></h1>";
            } else {
                    echo "<h1>Eliminazione non effettuata. Per riprovare clicca <a href='visualizza.php'>qui</a></h1>";
            }
        } else {
            echo "<h2>Impossibile cancellare questa prenotazione perche non presente nel database.</h2>";
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