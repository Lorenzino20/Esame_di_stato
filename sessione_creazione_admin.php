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
    if(!isset($_POST['creazione_utente']) && !isset($_POST['creazione_skipass']) && !isset($_POST['creazione_impianto'])) 
        header("Location: error.html");
    if(isset($_POST['creazione_utente'])) {
        $cognome = mysql_real_escape_string($_POST['cognome']);  //posiziona eventuali backslash prima di caratteri come \x00(Excadecimal notation), \n(New line), \r(Return), \, ', ", \x1a(End Of File)
        $nome = mysql_real_escape_string($_POST['nome']); 
        $email = mysql_real_escape_string($_POST['email']);
        $telefono = $_POST['telefono'];
        $codiceFiscale = $_POST['codiceFiscale'];
        $psw = mysql_real_escape_string($_POST['psw']);
        $pswcry = hash("SHA512", $psw);
        $admin = $_POST['admin'];

        $numeriN =  preg_match('/[0-9]/', $nome); 
        $numeriC = preg_match('/[0-9]/', $cognome); 
        $maiuscoleCF = preg_match('/[A-Z]/', $codiceFiscale);
        $minuscoloCF = preg_match('/[a-z]/', $codiceFiscale);
        $numeriCF = preg_match('/[0-9]/', $codiceFiscale);
        $specialeCF = preg_match('@[^\w]@', $codiceFiscale);
        $numeriTel = preg_match('/[0-9]/', $telefono);
        $maiuscoloPW = preg_match('/[A-Z]/', $psw);
        $numeriPW = preg_match('/[0-9]/', $psw);
        $specialePW = preg_match('@[^\w]@', $psw); 

        if($numeriN || $numeriC) 
            echo "<h3 class='comment'>il nome e il cognome non possono avere numeri</h3>";
        else if(!$maiuscoleCF || !$numeriCF || strlen($codiceFiscale) <> 16 || $specialeCF || $minuscoloCF)
            echo "<h3 class='comment'>inserire il codice fiscale in maiuscolo, controllando tutti i caratteri(inserire numeri e lettere, numero di caratteri deve essere pari a 16)</h3>";
        else if(!$numeriTel || strlen($telefono) <> 10) 
            echo "<h3 class='comment'>il telefono deve essere composto da 10 numeri</h3>";
        else if(!$maiuscoloPW || !$numeriPW || strlen($psw) < 8 || !$specialePW)
            echo "<h3 class='comment'>la password deve essere composta da almeno 8 caratteri tra cui una maiuscola un numero e un carattere speciale</h3>";
        else {
            $database = mysql_connect("localhost", "root", "")
            or die ("Errore di connessione al database");
            
            mysql_select_db("esame2")
            or die ("Errore connessione con database");

            $testo = "INSERT INTO utente (codice_fiscale, nome, cognome, email, telefono, password, admin)
            VALUES ('$codiceFiscale', '$nome', '$cognome', '$email', $telefono, '$pswcry', '$admin')";
            $query = mysql_query($testo);
            if($query) {
                echo "<div class='comment'><h2>Creazione utente effettuata con successo</h2></div>";
            } else {
                echo "<div class='comment'><h2>Creazione utente non effettuata</h2></div>";
            }
        }
    }
    if(isset($_POST['creazione_skipass'])) {
        $database = mysql_connect("localhost", "root", "")
            or die ("Errore di connessione al database");
            
        mysql_select_db("esame2")
        or die ("Errore connessione con database");
        $codiceFiscale = $_POST['codiceFiscale'];
        $piano = $_POST['piano'];

        $testo = "SELECT utente.id_utente FROM utente WHERE utente.codice_fiscale = '$codiceFiscale'";
        $query = mysql_query($testo);
        $riga = mysql_num_rows($query);
        if($riga <> 0) {
            $testo = "SELECT utente.id_utente FROM utente WHERE utente.codice_fiscale = '$codiceFiscale'";
            $query = mysql_query($testo);
            $righe = mysql_fetch_array($query);
            $id_utente = $righe['id_utente'];
            $oggi = date ("Y/m/d");
            $due_mesi = strtotime('+60 day', strtotime($oggi));
            $due_mesi = date('Y/m/d', $due_mesi);
            $testo = "INSERT INTO skipass(risalite_rimanenti, data_attivazione, data_scadenza, id_utente, risalite_piano) VALUES ('$piano', '$oggi', '$due_mesi', '$id_utente', '$piano')";
            $query = mysql_query($testo);
            if($query) {
                echo "<div class='comment'><h2>Creazione skipass effettuata con successo</h2></div>";
            } else {
                echo "<div class='comment'><h2>Creazione skipass non effettuata</h2></div>";
            }
        } else {
            echo "<div class='comment'><h2>Impossibile utilizzare questo codice fiscale. Non esiste un utente con questo identificativo.</h2></div>";
        }
    }
    if(isset($_POST['creazione_impianto'])) {
        $database = mysql_connect("localhost", "root", "")
            or die ("Errore di connessione al database");
            
        mysql_select_db("esame2")
        or die ("Errore connessione con database");

        $nome = $_POST['nome_impianto'];
        $descrizione = $_POST['descrizione'];
        $posti_totali = $_POST['posti_totali'];
        $orario_apertura = $_POST['orario_apertura'];
        $orario_chiusura = $_POST['orario_chiusura'];

        if($orario_chiusura < $orario_apertura) {
            echo "<div class='comment'><h2>Errore inserimento orario</h2></div>";
            exit();
        } 
        $testo = "SELECT * FROM impianto_risalita where impianto_risalita.nome = '$nome'";
        $query = mysql_query($testo);
        $riga = mysql_num_rows($query);
        if($riga == 0) {
            $testo = "INSERT INTO impianto_risalita (nome, descrizione, posti_totali, orario_apertura, orario_chiusura) VALUES ('$nome', '$descrizione', '$posti_totali', '$orario_apertura', '$orario_chiusura')";
            echo "$testo";
            $query = mysql_query($testo);
            if($query) {
                echo "<div class='comment'><h2>Creazione skipass effettuata con successo</h2></div>";
            } else {
                echo "<div class='comment'><h2>Creazione skipass non effettuata</h2></div>";
            }
        } else {
            echo "<div class='comment'><h2>Impossibile utilizzare questo nome. Già esiste un impianto con questo identificativo.</h2></div>";
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