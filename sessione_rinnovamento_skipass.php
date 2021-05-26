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
                <li class="current"><a href="modifica_utente.php">Rinnova skipass</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
<div class="area_cliente">
<?php
    if(isset($_POST['piano'])) {
        session_start();
        $piano = $_POST['piano'];

        $database = mysql_connect("localhost", "root", "")
            or die ("Errore di connessione al database");
                
        mysql_select_db("esame2")
            or die ("Errore connessione con database");
    
        $codice_fiscale = $_SESSION['codice_fiscale'];
    
        $testo = "SELECT * FROM utente U, skipass S WHERE U.id_utente = S.id_utente AND
        U.codice_fiscale = '$codice_fiscale'";
        $query = mysql_query($testo);
        $riga = mysql_num_rows($query);
        if($riga <> 0) {
            $testo = "SELECT S.id_tessera FROM utente U, skipass S WHERE U.id_utente = S.id_utente AND
            U.codice_fiscale = '$codice_fiscale'";
            $query = mysql_query($testo);
            $righe = mysql_fetch_array($query);
            $id_tessera = $righe['id_tessera'];
            $oggi = date ("Y/m/d");
            $due_mesi = strtotime('+60 day', strtotime($oggi));
            $due_mesi = date('Y/m/d', $due_mesi);
            $testo = "UPDATE skipass SET risalite_rimanenti='$piano', data_attivazione='$oggi', data_scadenza='$due_mesi', risalite_piano='$piano' WHERE id_tessera = '$id_tessera'";
            $query = mysql_query($testo);
            if($query) {
                echo "<div class='comment'><h2>Rinnovo dello skipass effettuato con successo</h2></div>";
            } else {
                echo "<div class='comment'><h2>Rinnovo dello skipass non effettuato</h2></div>";
            }
        } else {
            echo "<div class='comment'><h2>Non esiste nessuno skipass associato. Per rinnovare uno skipass quest'ultimo deve esistere.</h2></div>";
        }
    } else {
        header("Location: error.html");
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