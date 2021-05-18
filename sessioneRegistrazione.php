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
                <li class="current"><a href="monitoraggio_impianti.php">Monitoraggio impianti</a></li>
                <li><a href="logout.php">Logout</a></li>
        </ul>
        </div>
    </div>
    <div class="area_cliente">
<?php
    session_start();
    //se si accede alla pagina tramite l'URL
    if(!isset($_SESSION['pass'])) {
        header("Location: error.html");
        exit(); //si conclude l'esecuzione 
    }
    $pass = $_SESSION['pass'];

    if($pass == true) { 
        //salvataggio dei valori della sessione
        $cognome = $_SESSION['cognome'];
        $nome = $_SESSION['nome'];
        $email = $_SESSION['email'];
        $telefono = $_SESSION['telefono'];
        $codiceFiscale = $_SESSION['codiceFiscale'];
        $admin = $_SESSION['admin'];
        
        $psw = $_SESSION['psw'];
        //creazione password criptata
        $pswcry = hash("sha512", $psw);
        //collegamento al server che contiene il database
        $database = mysql_connect("localhost", "root", "")
        or die("Errore connessione database");
        //collegamento al database covid
        mysql_select_db("esame2")
        or die("Errore connessione database");
        //stringa query
        $testo = "INSERT INTO utente (codice_fiscale, nome, cognome, email, telefono, password, admin)
        VALUES ('$codiceFiscale', '$nome', '$cognome', '$email', $telefono, '$pswcry', '$admin')";
        //si effettua la query
        $query = mysql_query($testo);
        if($query) {    //se è tutto corretto si stampa la stringa che collega al file di log
            echo "<h1>Registrazione effettuata procedi con il log <a href='login.html'>qui</a></h1>";
            session_destroy();
        }
        else {  //se c'è un errore si stampa la stringa che collega al file di registrazione 
            echo "<h1>Errore nella registrazione riprova <a href='registrazione.php'>qui</a></h1>"; 
            session_destroy();
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