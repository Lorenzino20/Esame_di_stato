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
    if(!isset($_POST['modifica_utente']) && !isset($_POST['elimina_utente']) && !isset($_POST['elimina_tessera']) && !isset($_POST['elimina_prenotazione'])) 
        header("Location: error.html");
    if(isset($_POST['elimina_tessera'])) {
        $database = mysql_connect("localhost", "root", "")
        or die("Errore di connessione al database");
    
        mysql_select_db("esame2")
        or die ("Errore connessione con database");

        $id_tessera = $_POST['id_tessera'];
        $testo = "DELETE FROM skipass WHERE skipass.id_tessera = '$id_tessera'";
        $query = mysql_query($testo);

        if($query) {
            echo "<h2>Eliminazione tessera effettuata ora torna all' Area clienti e verifica se le modifiche sono visibili.
            Per accedere alla home clicca <a href='admin.php'>qui</a></h2>";
        } else {
            echo "<h2>Eliminazione non effettuata.
            Per accedere alla home clicca <a href='admin.php'>qui</a></h2>";
        }
        exit();
    }
    if(isset($_POST['elimina_utente'])) {
        $id_utente = $_SESSION['id_utente'];
        $testo = "DELETE FROM utente WHERE utente.id_utente = '$id_utente'";
        $query = mysql_query($testo);

        if($query) {
            echo "<h2>Eliminazione utente effettuata ora torna all' Area clienti e verifica se le modifiche sono visibili.
            Per accedere alla home clicca <a href='admin.php'>qui</a></h2>";
        } else {
            echo "<h2>Eliminazione non effettuata.
            Per accedere alla home clicca <a href='admin.php'>qui</a></h2>";
        }
        exit();
    } if(isset($_POST['elimina_prenotazione'])) {
        $id_risalita = $_SESSION['id_risalita'];
        $id_tessera = $_POST['id_tessera'];
        $orario = $_POST['orario'];

        $testo = "DELETE FROM prenota WHERE prenota.id_tessera = '$id_tessera' AND prenota.id_risalita = '$id_risalita' AND prenota.orario = '$orario'";
        $query = mysql_query($testo);
        if($query) {
            echo "<h2>Eliminazione prenotazione effettuata ora torna all' Area clienti e verifica se le modifiche sono visibili.
            Per accedere alla home clicca <a href='admin.php'>qui</a></h2>";
        } else {
            echo "<h2>Eliminazione non effettuata.
            Per accedere alla home clicca <a href='admin.php'>qui</a></h2>";
        }
        
        $codice_fiscale = $_SESSION['codice_fiscale'];
        $testo = "SELECT S.risalite_rimanenti AS risalite_rimanenti
        FROM skipass S, utente U
        WHERE S.id_utente = U.id_utente AND U.codice_fiscale = '$codice_fiscale'";
        echo "$testo";
        $query = mysql_query($testo);
        $righe = mysql_fetch_array($query);
        $risalite_rimanenti = $righe['risalite_rimanenti'];
        $risalite = $risalite_rimanenti + 1;
        
        $testo = "UPDATE skipass SET risalite_rimanenti= $risalite WHERE id_tessera = '$id_tessera'";
        $query = mysql_query($testo);

        if($query) {
            echo "<h2>Aggiornamento numero risalite effettuata ora torna all' Area clienti e verifica se le modifiche sono visibili.
            Per accedere alla home clicca <a href='admin.php'>qui</a></h2>";
        } else {
            echo "<h2>Aggiornamento non effettuata.
            Per accedere alla home clicca <a href='admin.php'>qui</a></h2>";
        }

        exit();
    } else { 
        
        $database = mysql_connect("localhost", "root", "")
        or die("Errore di connessione al database");
    
        mysql_select_db("esame2")
        or die ("Errore connessione con database");
        $codice_fiscale = $_SESSION['codice_fiscale'];
        $nome = $_POST['nome'];
        $cognome = $_POST['cognome'];
        $email = $_POST['email'];
        $telefono = $_POST['telefono'];
        $risalite_rimanenti = $_POST['risalite_rimanenti'];
        $data_attivazione = $_POST['data_attivazione'];
        $data_scadenza = $_POST['data_scadenza'];
        $id_tessera = $_POST['id_tessera'];

        $testo = "UPDATE utente SET nome = '$nome', cognome = \"$cognome\", telefono = '$telefono', 
        email = '$email' WHERE codice_fiscale = '$codice_fiscale'";
        $query = mysql_query($testo);

        if($query) {
            echo "<h2>Modifica effettuata ora torna all' Area clienti e verifica se le modifiche sono visibili.
            Per accedere alla home clicca <a href='admin.php'>qui</a></h2>";
        } else {
            echo "<h2>Modifica non effettuata.
            Per accedere alla home clicca <a href='admin.php'>qui</a></h2>";
        }

        $testo = "UPDATE skipass SET risalite_rimanenti = '$risalite_rimanenti',
        data_attivazione = '$data_attivazione', data_scadenza = '$data_scadenza'
        WHERE id_tessera = '$id_tessera'";
        $query = mysql_query($testo);
        if($query) {
            echo "<h2>Modifica effettuata ora torna all' Area clienti e verifica se le modifiche sono visibili.
            Per accedere alla home clicca <a href='admin.php'>qui</a></h2>";
        } else {
            echo "<h2>Modifica non effettuata.
            Per accedere alla home clicca <a href='admin.php'>qui</a></h2>";
        }
    }   
    mysql_close();
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
