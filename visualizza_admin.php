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
                <li><a href="logout.php">Logout</a></li>
                <li><a href="admin.php">Area ADMIN</a></li>
                <li class="current"><a href="visualizza_admin.php">Visualizza prenotazioni</a></li>
            </ul>
        </div>
    </div>
    <div class="area_cliente">
    <div class="ricerca"> 
        <h1>Ricerca una persona tra i prenotati</h1>
        <form method="POST" action="visualizza_admin.php"> 
        <h2>Ricerca nome</h2>
        <input type="text" name="ricerca_nome" placeholder="Ricerca nome" required>
        <h2>Ricerca cognome</h2>
        <input type="text" name="ricerca_cognome" placeholder="Ricerca cognome" required>
        <input type="submit" value="Ricerca" name="ricerca1">   
    </form>
    </div>
<?php
    session_start();
    if(!isset($_SESSION['admin'])) {
        header("Location: error.html");
        exit();
    } else {
        $database = mysql_connect("localhost", "root", "")
        or die("Errore di connessione al database");

        mysql_select_db("esame2")
        or die ("Errore connessione con database");

        if(isset($_POST['ricerca1'])) {
            $nome = $_POST['ricerca_nome'];
            $cognome = $_POST['ricerca_cognome'];
            $testo = "SELECT U.codice_fiscale AS codice_fiscale, U.nome AS nome, U.cognome AS cognome, U.email AS email,
            U.telefono AS telefono, S.id_tessera AS id_tessera, S.risalite_rimanenti AS risalite_rimanenti, 
            S.data_attivazione AS data_attivazione, S.data_scadenza AS data_scadenza,
            I.nome AS nome_impianto, I.descrizione AS descrizione, I.posti_totali AS posti_totali, P.orario AS orario  
            FROM prenota P, utente U, skipass S, impianto_risalita I 
            WHERE S.id_utente = U.id_utente AND P.id_risalita = I.id_risalita AND P.id_tessera = S.id_tessera
            AND U.nome = '$nome' AND U.cognome = '$cognome' ";
        } else {
            $testo = "SELECT U.codice_fiscale AS codice_fiscale, U.nome AS nome, U.cognome AS cognome, U.email AS email,
            U.telefono AS telefono, S.id_tessera AS id_tessera, S.risalite_rimanenti AS risalite_rimanenti, 
            S.data_attivazione AS data_attivazione, S.data_scadenza AS data_scadenza,
            I.nome AS nome_impianto, I.descrizione AS descrizione, I.posti_totali AS posti_totali, P.orario AS orario  
            FROM prenota P, utente U, skipass S, impianto_risalita I 
            WHERE S.id_utente = U.id_utente AND P.id_risalita = I.id_risalita AND P.id_tessera = S.id_tessera";
        }

        $query = mysql_query($testo);
        if($query) {
            echo "<div class='tabella_utenti'>
            <table class='utenti'><tr><td>Tabella prenotazioni</td></tr>
            <tr>
            <td>Codice fiscale</td>
            <td>Nome</td>
            <td>Cognome</td>
            <td>Email</td>
            <td>Telefono</td>
            <td>Id tessera</td>
            <td>Risalite rimanenti</td>
            <td>Data attivazione</td>
            <td>Data scadenza</td>
            <td>Nome impianto</td>
            <td>Descrizione</td>
            <td>Posti</td>
            <td>Orario</td>
            </tr>";
            while($righe = mysql_fetch_array($query)) {
                echo "<tr>
                <td>$righe[codice_fiscale]</td>
                <td>$righe[nome]</td>
                <td>$righe[cognome]</td>
                <td>$righe[email]</td>
                <td>$righe[telefono]</td>
                <td>$righe[id_tessera]</td>
                <td>$righe[risalite_rimanenti]</td>
                <td>$righe[data_attivazione]</td>
                <td>$righe[data_scadenza]</td>
                <td>$righe[nome_impianto]</td>
                <td>$righe[descrizione]</td>
                <td>$righe[posti_totali]</td>
                <td>$righe[orario]</td>
                <td>Modifica</td>
                </tr>";
            }
            echo "</table></div>";
        } else {
            echo "<h2>L'utente non ha effettuato nessuna prenotazione.</h2>";
        }

        echo "<hr class='separatore'>";
        echo "<div class='ricerca'> 
        <form method='POST' action='visualizza_admin.php'> 
        <h1>Ricerca una persona tra i clienti</h1>
        <h2>Ricerca nome</h2>
        <input type='text' name='ricerca_nome2' placeholder='Ricerca nome' required>
        <h2>Ricerca cognome</h2>
        <input type='text' name='ricerca_cognome2' placeholder='Ricerca cognome' required>
        <input type='submit' value='Ricerca' name='ricerca2'>   
    </form>
    </div>";
        if(isset($_POST['ricerca2'])) {
            $nome = $_POST['ricerca_nome2'];
            $cognome = $_POST['ricerca_cognome2'];
            $testo = "SELECT * FROM utente U, skipass S WHERE U.id_utente = S.id_utente
            AND U.nome = '$nome' AND U.cognome = \"$cognome\"";
        } else {
            $testo = "SELECT * FROM utente U, skipass S WHERE U.id_utente = S.id_utente";
        }
        
        $query = mysql_query($testo);
        echo "<div class='tabella_utenti'>
        <table class='utenti'><tr><td>Tabella utenti</td></tr>
        <tr>
        <td>Id utente</td>
        <td>Codice fiscale</td>
        <td>Nome</td>
        <td>Cognome</td>
        <td>Email</td>
        <td>Telefono</td>
        <td>Id tessera</td>
        <td>Risalite rimanenti</td>
        <td>Data attivazione</td>
        <td>Data scadenza</td>
        </tr>";
        while($righe = mysql_fetch_array($query)) {
            echo "<tr>
            <td>$righe[id_utente]</td>
            <td>$righe[codice_fiscale]</td>
            <td>$righe[nome]</td>
            <td>$righe[cognome]</td>
            <td>$righe[email]</td>
            <td>$righe[telefono]</td>
            <td>$righe[id_tessera]</td>
            <td>$righe[risalite_rimanenti]</td>
            <td>$righe[data_attivazione]</td>
            <td>$righe[data_scadenza]</td>
            <td>Modifica</td>
            </tr>";
        }
        echo "</table></div>";
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