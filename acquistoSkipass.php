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
                <li><a href="index.html">Home</a></li>
                <li><a href="login.php">Login</a></li>
                <li class="current"><a href="acquistoSkipass.php">Acquisto Skipass</a></li>
            </ul>
        </div>
    </div>
    <div class='area_cliente'>
    <div>
        <h1 id='centro'>Acquista uno Skipass</h1>
    </div>
    <div class='skipass'>
<?php
    session_start();
    if(!isset($_SESSION['pass'])) {
        header("Location: error.html");
        exit();
    } else {
        echo "
        <div class='card'>
            <h1>I tuoi dati:</h1>";
        $database = mysql_connect("localhost", "root", "")
        or die("Errore di connessione al database");

        mysql_select_db("esame2")
        or die ("Errore connessione con database");

        $email = $_SESSION['email'];
        $testo = "SELECT * FROM utente WHERE email = '$email'";

        $query = mysql_query($testo);
        $riga = mysql_fetch_array($query);
        $id_utente = $riga['id_utente'];
        $nome = $riga['nome'];
        $cognome = $riga['cognome'];
        $codice_fiscale = $riga['codice_fiscale'];
        $_SESSION['codice_fiscale'] = $codice_fiscale;
        $_SESSION['id_utente'] = $id_utente;

        if($riga == 0) {
            session_destroy();
            echo "Attenzione - Nome utente o password errata. Riprova il log <a href='login.html'>qui</a>";
        } else {
           echo "<h2>Nome: $nome</h2>";
           echo "<h2>Cognome: $cognome</h2>";
           echo "<h2>Codice Fiscale: $codice_fiscale</h2>";
        }

        $testo = "SELECT * FROM skipass WHERE id_utente = '$id_utente'";
        $query = mysql_query($testo);
        
        $riga = mysql_num_rows($query);

        if($riga != 0) {
            echo "<h2>Per prenotare clicca <a href='prenotazione.php'>qui</a></h2>";
            echo "<h2>Altrimenti visualizza le tue prossime risalite già prenotate <a href='visualizza.php'>qui</a></h2>";
            exit();
        } else {
            echo "<h2>Non sono associati skipass a questo utente. Acquistane uno qui!</h2>";
        }
        mysql_close();
    }
?>
    </div>
        <div class="piani">
            <form action="creazioneSkipass.php" method="POST">
                <h1>Seleziona un piano:</h1>
                <h2>Piano 1</h2>
                <p>Numero di risalite: 5</p>
                <input type="radio" id="piano" name="piano" value="5">
                <h2>Piano 2</h2>
                <p>Numero di risalite: 10</p>
                <input type="radio" id="piano" name="piano" value="10">
                <h2>Piano 3</h2>
                <p>Numero di risalite: 15</p>
                <input type="radio" id="piano" name="piano" value="15"><br/>
                <input id='submit' type="submit" value="invia">
            </form>
        </div>
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