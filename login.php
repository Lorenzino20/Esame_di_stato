<!DOCTYPE html> 
<html>        
    <head>
        <meta charset="UTF-8">
        <title>Registrazione</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div class="header">
            <div class="logo">
                <a href="index.html"><img src="Logo/logo_small.png"></a>
            </div>
            <div class="menu">
                <ul class="menu-items">
                    <li><a href="index.html">Home</a></li>
                    <li><a href="login.html">Login</a></li>
                    <li class="current"><a href="registrazione.php">Registrazione</a></li>
                </ul>
            </div>
        </div>
        <div class="area_cliente">
<?php
    session_start();
    //se non sono impostate la mail e la password non si può procedere
    if(!isset($_POST['email']) || !isset($_POST['psw'])) {
        echo "Impossibile accedere senza compilare il <a href='login.html'>Form di log</a>";
        exit();
    } else {
            //salvataggio dei valori del form 
            $email = $_POST['email'];
            $psw = mysql_real_escape_string($_POST['psw']);
            $pswcry = hash("sha512", $psw);
            //collegamento al server che contiene il database
            $database = mysql_connect("localhost", "root","") or 
            die ("Errore connessione al database");
            //collegamento al database covid
            mysql_select_db("esame2")
            or die ("Errore nella connessione al database");
            //stringa query
            $testo = "SELECT * FROM utente WHERE email = '$email' AND password = '$pswcry'";
            //si effettua la query
            $query = mysql_query($testo);
            $riga = mysql_num_rows($query);
            //se esiste un campo vuol dire che esiste l'utente
            if($riga <> 0) {
                //collegamento con il file sessioneLog
                $_SESSION['email'] = $email;
                $_SESSION['psw'] = $psw;
                $_SESSION['pass'] = true;
                
                header('Location: sessioneLog.php');
                mysql_close();
            }
            else { //link al file di registrazione o log    se si sono inseriti dati errati o non ci si è ancora registrati
                echo "<h2>Errore nell'inserimento del nome o della password. Riprova <a href='login.html'>qui</a> oppure registrati <a href='registrazione.php'>qui</a></h2>";
                session_destroy();
                mysql_close();
            } 
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