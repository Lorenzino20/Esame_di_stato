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
            <h1>Ciao, sei nella sezione utente.<h1>
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
        $testo = "SELECT codice_fiscale 
        FROM utente
        WHERE utente.email = '$email'";
        $query = mysql_query($testo);
        $righe = mysql_fetch_array($query);
        $codice_fiscale = $righe['codice_fiscale'];
        $_SESSION['codice_fiscale'] = $codice_fiscale;
    
        $testo = "SELECT *
        FROM skipass
        Where skipass.id_utente = (SELECT id_utente 
        FROM utente
        WHERE utente.email = '$email')";
        $query = mysql_query($testo);
        $righe = mysql_num_rows($query);
    
        if($righe <> 0) {
            echo "<h2>Se vuoi prenotare una risalita clicca <a href='prenotazione.php'>qui</a>!</h2>
            <h2>Se vuoi visualizzare le tue prenotazioni clicca <a href='visualizza.php'>qui</a>!</h2>";
        } else {
            echo "<h2>Non hai ancora uno skipass associato. Acquistalo <a href='acquistoSkipass.php'>qui</a></h2>";
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