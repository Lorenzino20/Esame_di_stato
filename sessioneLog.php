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
                <li class="current"><a href="monitoraggio_impianti.php">Monitoraggio impianti</a></li>
                <li class="current">Qrcode</li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
<?php
    session_start();
    if(!isset($_SESSION['pass'])) {
        header("Location: error.html");
        exit();
    } else {
        $pass = $_SESSION['pass'];
        if($pass == true) {
            $email = $_SESSION['email'];
            $psw = $_SESSION['psw'];
            $_SESSION = Array();
            $_SESSION['email'] = $email;
            $pswcry = hash("sha512", $psw);
            
            $database = mysql_connect("localhost", "root","") or 
            die ("Errore connessione al database");
            
            mysql_select_db("esame2")
            or die ("Errore nella connessione al database");
            
            $testo = "SELECT * FROM utente WHERE email = '$email' AND password = '$pswcry'";
            
            $query = mysql_query($testo);
            $riga = mysql_num_rows($query);
            $righe = mysql_fetch_array($query);
            $admin = $righe['admin'];
        
            if($riga == 0) {
                session_destroy();
                echo "Attenzione - Nome utente o password errata. Riprova il log <a href='login.html'>qui</a>";
            } else {
                if($admin == true){
                    $_SESSION['admin'] = true;
                    header ('Location: admin.php');
                } else {
                    $_SESSION['pass'] = true;
                    header('Location: utente.php');
                }
            }
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