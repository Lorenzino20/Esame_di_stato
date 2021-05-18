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
                <li class="current"><a href="modifica_utente.php">Info utente</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
<?php 
    session_start();
    if(!isset($_SESSION['email'])) {
        header("Location: error.html");
        exit();
    } else {
        $email = $_SESSION['email'];
        $database = mysql_connect("localhost", "root", "")
        or die("Errore di connessione al database");
    
        mysql_select_db("esame2")
        or die ("Errore connessione con database");

        
        $testo = "SELECT * FROM utente WHERE email = '$email'";
        $query = mysql_query($testo);
        $righe = mysql_fetch_array($query);
        $nome = $righe['nome'];
        $cognome = $righe['cognome'];
        $telefono = $righe['telefono'];
        $email = $righe['email'];
        $codice_fiscale = $righe['codice_fiscale'];
        $_SESSION['codice_fisclae'] = $codice_fiscale;
        echo "<div class='modifica_dati'>
        <form method='POST' action='sessione_modifica.php'>
        <h2>Nome:</h2>
        <input name='nome' value='$nome'>
        <h2>Cognome:</h2>
        <input name='cognome' value=\"$cognome\">
        <h2>Telefono:</h2>
        <input name='telefono' value='$telefono'>
        <h2>Email:</h2>
        <input name='email' value='$email'>
        <br/>
        <input type='submit' name='modifica' value='Modifica'>
        </form>
        </div>";
        $testo = "SELECT * FROM skipass S WHERE S.id_utente = (SELECT U.id_utente FROM utente U WHERE U.codice_fiscale = '$codice_fiscale')";
        $query = mysql_query($testo);
        $righe = mysql_num_rows($query);
        if($righe <> 0) {
            $righe = mysql_fetch_array($query);
            $risalite_rimanenti = $righe['risalite_rimanenti'];
            $data_attivazione = $righe['data_attivazione'];
            $data_scadenza = $righe['data_scadenza']; 
        
            
            echo "<div class='info_utente'>
            <h2>Risalite rimanenti: $risalite_rimanenti</h2>
            <h2>Data attivazione: $data_attivazione</h2>
            <h2>Data scadenza: $data_scadenza</h2>
            </div>";
            if($risalite_rimanenti == 0 || $data_scadenza < date("Y-m.d")) {
                echo "<h2>Per rinnovare lo skipass clicca <a href='rinnovamentoSkipass.php'>qui</a></h2>";
            }
        } else {
            echo "<h2>Impossibile visualizzare i dati dello skipass. Per farlo devi acquistarne uno <a href='acquistoSkipass.php'>qui</a></h2>";
        }
        
        
        mysql_close();
    }
    
?>
<div class="footer">
        <span>Sito realizzato da Lorenzo D'Amico</span>
        <span>Copyright©2020 LDA SpA</span>
        <span>Tutti i diritti riservati</span>
        <i class="fab fa-facebook-f"></i>
        <i class="fab fa-instagram"></i>
    </div>
</body>
</html>