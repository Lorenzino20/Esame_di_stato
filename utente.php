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
    session_start();
    if(!isset($_SESSION['pass'])) {
        header("Location: error.html");
        exit();
    } else {
        $email = $_SESSION['email'];
        $database = mysql_connect("localhost", "root", "")
        or die("Errore di connessione al database");
    
        mysql_select_db("esame2")
        or die ("Errore connessione con database");
        $testo = "SELECT U.codice_fiscale AS codice_fiscale, U.nome AS nome, U.cognome AS cognome  
        FROM utente U
        WHERE U.email = '$email'";
        $query = mysql_query($testo);
        $righe = mysql_fetch_array($query);
        $codice_fiscale = $righe['codice_fiscale'];
        $nome = $righe['nome'];
        $cognome = $righe['cognome'];
        echo "<div class='utente'>
            <h3>Benvenuto $nome $cognome</h3>
        </div>";
        $_SESSION['codice_fiscale'] = $codice_fiscale;

    
        $testo = "SELECT *
        FROM skipass
        Where skipass.id_utente = (SELECT id_utente 
        FROM utente
        WHERE utente.email = '$email')";
        $query = mysql_query($testo);
        $righe = mysql_num_rows($query);
    
        if($righe <> 0) {
            echo "
            <div id='home_utente'>
                <h2 id='item'>Se vuoi prenotare una risalita clicca qui!</h2><a href='prenotazione.php'><img src='Immagini/prenota.png'></img></a>
                <h2 id='item'>Se vuoi visualizzare le tue prenotazioni clicca qui!</h2><a href='visualizza.php'><img src='Immagini/prenotazioni.png'></img></a>
            </div>";
        } else {
            echo "<div class='comment'><h2>Non hai ancora uno skipass associato. Acquistalo <a href='acquistoSkipass.php'>qui</a></h2></div>";
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