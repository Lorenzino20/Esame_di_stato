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
    <div class='area_cliente'>
        <h1 id='centro'>Rinnova il tuo Skipass</h1>
    <div class="rinnovo">
<?php
session_start();
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
       echo "<h2>Codice Fiscale: $codice_fiscale</h2></div>";
    }
?>
    <div class="form_rinnovo">
        <form action="sessione_rinnovamento_skipass.php" method="POST">
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
            <input id='rinnovo' type="submit" value="Rinnova">
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