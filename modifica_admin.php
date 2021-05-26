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
                <li><a href="visualizza_admin.php">Visualizza prenotazioni</a></li>
                <li class="current"><a href="modifica_admin.php">Modifica</a></li>
            </ul>
        </div>
    </div>
    <div class='area_admin'>
<?php
    session_start();
    $database = mysql_connect("localhost", "root", "")
    or die("Errore di connessione al database");

    mysql_select_db("esame2")
    or die ("Errore connessione con database");

    if(isset($_GET['codice_fiscale']) && isset($_GET['id_tessera']) && isset($_GET['orario'])) {
        $codice_fiscale = $_GET['codice_fiscale'];
        $_SESSION['codice_fiscale'] = $codice_fiscale;
        $testo = "SELECT U.codice_fiscale AS codice_fiscale, U.nome AS nome, U.cognome AS cognome, U.email AS email,
        U.telefono AS telefono, S.id_tessera AS id_tessera, S.risalite_rimanenti AS risalite_rimanenti, 
        S.data_attivazione AS data_attivazione, S.data_scadenza AS data_scadenza, I.id_risalita AS id_risalita,
        I.nome AS nome_impianto, I.descrizione AS descrizione, I.posti_totali AS posti_totali, P.orario AS orario  
        FROM prenota P, utente U, skipass S, impianto_risalita I 
        WHERE S.id_utente = U.id_utente AND P.id_risalita = I.id_risalita AND P.id_tessera = S.id_tessera AND U.codice_fiscale = '$codice_fiscale'";
        
        $query = mysql_query($testo);
        $righe = mysql_fetch_array($query);
        $nome = $righe['nome'];
        $cognome = $righe['cognome'];
        $email = $righe['email'];
        $telefono = $righe['telefono'];
        $id_tessera = $righe['id_tessera'];
        $risalite_rimanenti = $righe['risalite_rimanenti'];
        $data_attivazione = $righe['data_attivazione'];
        $data_scadenza = $righe['data_scadenza'];
        $nome_impianto = $righe['nome_impianto'];
        $descrizione = $righe['descrizione'];
        $posti_totali = $righe['posti_totali'];
        $orario = $righe['orario'];
        $id_risalita = $righe['id_risalita'];
        $_SESSION['id_risalita'] = $id_risalita;

        echo "<div class='modifica_dati'>
        <div class='modifica_dati_form'>
        <form method='POST' action='sessione_modifica_admin.php'>
        <h2>Nome:</h2>
        <input name='nome' value='$nome' required>
        <h2>Cognome:</h2>
        <input name='cognome' value=\"$cognome\" required>
        <h2>Telefono:</h2>
        <input name='telefono' value='$telefono' required>
        <h2>Email:</h2>
        <input name='email' value='$email' required>
        <h2>Id_tessera:</h2>
        <input name='id_tessera' value='$id_tessera' readonly>
        <h2>Risalite rimanenti:</h2>
        <input name='risalite_rimanenti' value='$risalite_rimanenti' required>
        <h2>Data attivazione:</h2>
        <input name='data_attivazione' value='$data_attivazione' required>
        <h2>Data scadenza:</h2>
        <input name='data_scadenza' value='$data_scadenza' required>
        <h2>Nome impianto:</h2>
        <input name='nome_impianto' value='$nome_impianto' readonly>
        <h2>Descrizione:</h2>
        <input name='descrizione' value='$descrizione' readonly>
        <h2>Posti totali:</h2>
        <input name='posti_totali' value='$posti_totali' readonly>
        <h2>Orario:</h2>
        <input name='orario' value='$orario' required>
        <input type='submit' name='modifica_admin' value='Modifica'>
        <input type='submit' name='elimina_prenotazione' value='Cancella prenotazione'>
        </form>
        </div>
        </div>";
    } 
    else if(isset($_GET['id_utente']) && isset($_GET['id_tessera']) && !isset($_GET['codice_fiscale'])) {
        $id_utente = $_GET['id_utente'];
        $_SESSION['id_utente'] = $id_utente;
        $testo = "SELECT U.codice_fiscale AS codice_fiscale FROM utente U WHERE U.id_utente = '$id_utente'";
        $query = mysql_query($testo);
        $righe = mysql_fetch_array($query);
        $codice_fiscale = $righe['codice_fiscale'];
        $_SESSION['codice_fiscale'] = $codice_fiscale;
        $testo = "SELECT U.codice_fiscale AS codice_fiscale, U.nome AS nome, U.cognome AS cognome, U.email AS email,
        U.telefono AS telefono, S.id_tessera AS id_tessera, S.risalite_rimanenti AS risalite_rimanenti, 
        S.data_attivazione AS data_attivazione, S.data_scadenza AS data_scadenza, S.risalite_piano AS risalite_piano 
        FROM utente U, skipass S
        WHERE S.id_utente = U.id_utente AND U.id_utente = '$id_utente'";
        $query = mysql_query($testo);
        $righe = mysql_fetch_array($query);
        $nome = $righe['nome'];
        $cognome = $righe['cognome'];
        $email = $righe['email'];
        $telefono = $righe['telefono'];
        $id_tessera = $righe['id_tessera'];
        $risalite_rimanenti = $righe['risalite_rimanenti'];
        $risalite_piano = $righe['risalite_piano'];
        $data_attivazione = $righe['data_attivazione'];
        $data_scadenza = $righe['data_scadenza'];

        echo "<div class='modifica_dati'>
        <div class='modifica_dati_form'>
        <form method='POST' action='sessione_modifica_admin.php'>
        <h2>Nome:</h2>
        <input name='nome' value='$nome' required>
        <h2>Cognome:</h2>
        <input name='cognome' value=\"$cognome\" required>
        <h2>Telefono:</h2>
        <input name='telefono' value='$telefono' required>
        <h2>Email:</h2>
        <input name='email' value='$email' required>
        <h2>Id_tessera:</h2>
        <input name='id_tessera' value='$id_tessera' readonly>
        <h2>Risalite rimanenti:</h2>
        <input name='risalite_rimanenti' value='$risalite_rimanenti' required>
        <h2>Risalite piano:</h2>
        <input name='risalite_piano' value='$risalite_piano' readonly>
        <h2>Data attivazione:</h2>
        <input name='data_attivazione' value='$data_attivazione' required>
        <h2>Data scadenza:</h2>
        <input name='data_scadenza' value='$data_scadenza' required>
        <input type='submit' name='modifica_utente' value='Modifica'>
        <input type='submit' name='elimina_utente' value='Cancella utente'>
        <input type='submit' name='elimina_tessera' value='Cancella tessera'>
        </form>
        </div>
        </div>";
    } else if(isset($_GET['id_utente']) && !isset($_GET['id_tessera']) && !isset($_GET['codice_fiscale'])) {
        $id_utente = $_GET['id_utente'];
        $_SESSION['id_utente'] = $id_utente;
        $testo = "SELECT U.codice_fiscale AS codice_fiscale FROM utente U WHERE U.id_utente = '$id_utente'";
        $query = mysql_query($testo);
        $righe = mysql_fetch_array($query);
        $codice_fiscale = $righe['codice_fiscale'];
        $_SESSION['codice_fiscale'] = $codice_fiscale;
        $testo = "SELECT U.codice_fiscale AS codice_fiscale, U.nome AS nome, U.cognome AS cognome, U.email AS email,
        U.telefono AS telefono
        FROM utente U
        WHERE U.id_utente = '$id_utente'";
        $query = mysql_query($testo);
        $righe = mysql_fetch_array($query);
        $nome = $righe['nome'];
        $cognome = $righe['cognome'];
        $email = $righe['email'];
        $telefono = $righe['telefono'];

        echo "<div class='modifica_dati'>
        <div class='modifica_dati_form'>
        <form method='POST' action='sessione_modifica_admin.php'>
        <h2>Nome:</h2>
        <input name='nome' value='$nome' required>
        <h2>Cognome:</h2>
        <input name='cognome' value=\"$cognome\" required>
        <h2>Telefono:</h2>
        <input name='telefono' value='$telefono' required>
        <h2>Email:</h2>
        <input name='email' value='$email' required>
        <h2>Id_tessera:</h2>
        <input type='submit' name='modifica_utente' value='Modifica'>
        <input type='submit' name='elimina_utente' value='Cancella utente'>
        </form>
        </div>
        </div>";
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


