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
                <li><a href="creazione_admin.php">Crea un utente</a></li>
            </ul>
        </div>
    </div>
    <div class="area_admin">
    <div class="ricerca_admin"> 
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
            AND U.nome = '$nome' AND U.cognome = \"$cognome\"";
        } else {
            $testo = "SELECT U.codice_fiscale AS codice_fiscale, U.nome AS nome, U.cognome AS cognome, U.email AS email,
            U.telefono AS telefono, S.id_tessera AS id_tessera, S.risalite_rimanenti AS risalite_rimanenti, 
            S.data_attivazione AS data_attivazione, S.data_scadenza AS data_scadenza, 
            I.nome AS nome_impianto, I.descrizione AS descrizione, I.posti_totali AS posti_totali, P.orario AS orario  
            FROM prenota P, utente U, skipass S, impianto_risalita I 
            WHERE S.id_utente = U.id_utente AND P.id_risalita = I.id_risalita AND P.id_tessera = S.id_tessera";
        }

        $query = mysql_query($testo);
        $riga = mysql_num_rows($query);
        if($riga <> 0) {
            echo "<div class='tabella_prenotazioni_admin'>
            <table class='prenotazioni_admin'><tr><td>Tabella prenotazioni</td></tr>
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
                <td><a href=\"modifica_admin.php?codice_fiscale=".$righe['codice_fiscale']."&id_tessera=".$righe['id_tessera']."&orario=".$righe['orario']."\">Modifica</a></td>
                </tr>";
            }
            echo "</table></div>";
        } else {
            echo "<h2>Non ci sono prenotazioni.</h2>";
        }

        echo "<hr class='separatore'>";
        echo "<div class='ricerca_admin'> 
        <h1>Ricerca una persona tra i clienti</h1>
        <form method='POST' action='visualizza_admin.php'> 
        <h2>Ricerca nome</h2>
        <input type='text' name='ricerca_nome2' placeholder='Ricerca nome'>
        <h2>Ricerca cognome</h2>
        <input type='text' name='ricerca_cognome2' placeholder='Ricerca cognome'>
        <h2>Ricerca email</h2>
        <input type='mail' name='ricerca_email2' placeholder='Ricerca email'>
        <input type='submit' value='Ricerca' name='ricerca2'>   
        </form>
        </div>";
        if(isset($_POST['ricerca2'])) {
            if(isset($_POST['ricerca_nome2']) && $_POST['ricerca_nome2'] <> "") {
                $nome = $_POST['ricerca_nome2'];
                $testo = "SELECT * FROM utente U WHERE U.nome = '$nome'";
            } else {
                $testo = "SELECT * FROM utente U";
            }

            if(isset($_POST['ricerca_cognome2']) && $_POST['ricerca_cognome2'] <> "") {
                $cognome = $_POST['ricerca_cognome2'];
                if(isset($_POST['ricerca_nome2'])) {
                    $testo = $testo." AND U.cognome = '$cognome'";
                } else {
                    $testo = $testo." WHERE U.cognome = '$cognome'";
                }
            } 

            if(isset($_POST['ricerca_email2']) && $_POST['ricerca_email2'] <> "") {
                $email = $_POST['ricerca_email2'];
                if(isset($_POST['ricerca_cognome2'])) {
                    $testo = $testo." AND U.email = '$email'";
                } else {
                    $testo = $testo." AND U.email = '$email'";
                }
            } 
        } else {
            $testo = "SELECT * FROM utente U";
        }
        
        $query = mysql_query($testo);
        $riga = mysql_num_rows($query);
        if($riga <> 0) {
            echo "<div class='tabella_utenti_admin'>
            <table class='utenti_admin'><tr><td>Tabella utenti</td></tr>
            <tr>
            <td>Id utente</td>
            <td>Codice fiscale</td>
            <td>Nome</td>
            <td>Cognome</td>
            <td>Email</td>
            <td>Telefono</td>
            </tr>";
            while($righe = mysql_fetch_array($query)) {
                echo "<tr>
                <td>$righe[id_utente]</td>
                <td>$righe[codice_fiscale]</td>
                <td>$righe[nome]</td>
                <td>$righe[cognome]</td>
                <td>$righe[email]</td>
                <td>$righe[telefono]</td>
                <td><a href=\"modifica_admin.php?id_utente=".$righe['id_utente']."\">Modifica</a></td>
                </tr>";
            }
            echo "</table></div>";
        }
        echo "<hr class='separatore'>";
        echo "<div class='ricerca_admin'> 
        <h1>Ricerca una persona tra gli skipass</h1>
        <form method='POST' action='visualizza_admin.php'> 
        <h2>Ricerca nome</h2>
        <input type='text' name='ricerca_nome3' placeholder='Ricerca nome'>
        <h2>Ricerca cognome</h2>
        <input type='text' name='ricerca_cognome3' placeholder='Ricerca cognome'>
        <h2>Ricerca email</h2>
        <input type='mail' name='ricerca_email3' placeholder='Ricerca email'>
        <input type='submit' value='Ricerca' name='ricerca3'>   
        </form>
        </div>";
        if(isset($_POST['ricerca3'])) {
            if(isset($_POST['ricerca_nome3']) && $_POST['ricerca_nome3'] <> "") {
                $nome = $_POST['ricerca_nome3'];
                $testo = "SELECT * FROM utente U, skipass S WHERE U.id_utente = S.id_utente AND U.nome = '$nome'";
            } else {
                $testo = "SELECT * FROM utente U, skipass S WHERE U.id_utente = S.id_utente";
            }

            if(isset($_POST['ricerca_cognome3']) && $_POST['ricerca_cognome3'] <> "") {
                $cognome = $_POST['ricerca_cognome3'];
                if(isset($_POST['ricerca_nome3'])) {
                    $testo = $testo." AND U.cognome = '$cognome'";
                } else {
                    $testo = $testo."AND U.cognome = '$cognome'";
                }
            } 

            if(isset($_POST['ricerca_email3']) && $_POST['ricerca_email3'] <> "") {
                $email = $_POST['ricerca_email3'];
                if(isset($_POST['ricerca_cognome3'])) {
                    $testo = $testo." AND U.email = '$email'";
                } else {
                    $testo = $testo." AND U.email = '$email'";
                }
            } 
        } else {
            $testo = "SELECT * FROM utente U, skipass S WHERE U.id_utente = S.id_utente";
        }
        
        $query = mysql_query($testo);
        $riga = mysql_num_rows($query);
        if($riga <> 0) {
            echo "<div class='tabella_utenti_admin'>
            <table class='utenti_admin'><tr><td>Tabella utenti</td></tr>
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
            <td>Risalite piano</td>
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
                <td>$righe[risalite_piano]</td>
                <td><a href=\"modifica_admin.php?id_utente=".$righe['id_utente']."&id_tessera=".$righe['id_tessera']."\">Modifica</a></td>
                </tr>";
            }
            echo "</table></div>";
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