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
        <div class="main">
            <!-- Form di registrazione -->
            <div class="registrazione">
                <form action="registrazione.php" method="POST" >
                    <div class="title">
                        <h1>Registrazione</h1>
                    </div>
                    <div class="item">
                        <label for="nome">Nome</label>
                        <input type="text" name="nome" value="<?php if(isset($_POST['nome'])) echo $_POST['nome'] ?>" required>
                    </div>
                    <div class="item">
                        <label for="cognome">Cognome</label>
                        <input type="text" name="cognome" value="<?php if(isset($_POST['cognome'])) echo $_POST['cognome'] ?>" required >
                    </div>
                    <div class="item">
                        <label for="email">Email</label>
                        <input type="email" name="email" value="<?php if(isset($_POST['email'])) echo $_POST['email'] ?>" required >
                    </div>
                    <div class="item">
                        <label for="telefono">Telefono</label>
                        <input type="text" name="telefono" value="<?php if(isset($_POST['telefono'])) echo $_POST['telefono'] ?>" required>
                    </div>
                    <div class="item">
                        <label for="codiceFiscale">Codice fiscale</label>
                        <input type="text" name="codiceFiscale" value="<?php if(isset($_POST['codiceFiscale'])) echo $_POST['codiceFiscale'] ?>" required> 
                    </div>
                    <div class="item">
                        <label for="psw">Password</label>
                        <input type="password" name="psw" >
                    </div>
                    <div class="item">
                        <label for="repsw">Ripeti Password</label>
                        <input type="password" name="repsw" >
                    </div>
                    <div class="captcha">
                        <p>
                        <img src="./captcha.php"/>
                        </p>
                        <div class="button">
                            <input type="submit" value="Refresh" name="refresh" class="refresh">
                        </div>
                        <div class="item">
                            <label for="captcha">Captcha</label>
                            <input type="text" name="captcha">
                        </div>
                    </div> 
                    <div class="button">
                        <input type="submit" value="Registra" name="invia" class="submit">
                    </div>
                </form>
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
<?php
    if(!isset($_POST['invia'])) { //se non si è cliccato invia
        exit();     //non accade nulla viene solo ricaricata la pagina
    } else if(isset($_POST['refresh'])) 
        exit();     //non accade nulla viene solo ricaricata la pagina
    else {        
        session_start();
        //salvataggio dei valori del form
        $cognome = mysql_real_escape_string($_POST['cognome']);  //posiziona eventuali backslash prima di caratteri come \x00(Excadecimal notation), \n(New line), \r(Return), \, ', ", \x1a(End Of File)
        $nome = mysql_real_escape_string($_POST['nome']); 
        $email = mysql_real_escape_string($_POST['email']);
        $telefono = $_POST['telefono'];
        $codiceFiscale = $_POST['codiceFiscale'];
        $psw = mysql_real_escape_string($_POST['psw']);
        $repsw = mysql_real_escape_string($_POST['repsw']);
        $captcha = $_SESSION['captcha'];
        $pswcry = hash("sha512", $psw); //Secure Hash Algorithm
        //creazioni controlli sull'inserimento dei dati
        $numeriN =  preg_match('/[0-9]/', $nome);  //lo slash significa uno o più valori tra le parentesi devono essere presenti per ritornare true
        $numeriC = preg_match('/[0-9]/', $cognome); 
        $maiuscoleCF = preg_match('/[A-Z]/', $codiceFiscale);
        $minuscoloCF = preg_match('/[a-z]/', $codiceFiscale);
        $numeriCF = preg_match('/[0-9]/', $codiceFiscale);
        $specialeCF = preg_match('@[^\w]@', $codiceFiscale);
        $numeriTel = preg_match('/[0-9]/', $telefono);
        $maiuscoloPW = preg_match('/[A-Z]/', $psw);
        $numeriPW = preg_match('/[0-9]/', $psw);
        $specialePW = preg_match('@[^\w]@', $psw); //ritrona vero se trova caratteri che non siano word characters quindi da a-z A-Z 0-9 
        //controllo sull'inserimento
        if($numeriN || $numeriC) 
        echo "<h3 class='comment'>il nome e il cognome non possono avere numeri</h3>";
        else if(!$maiuscoleCF || !$numeriCF || strlen($codiceFiscale) <> 16 || $specialeCF || $minuscoloCF)
            echo "<h3 class='comment'>inserire il codice fiscale in maiuscolo, controllando tutti i caratteri(inserire numeri e lettere, numero di caratteri deve essere pari a 16)</h3>";
        else if(!$numeriTel || strlen($telefono) <> 10) 
            echo "<h3 class='comment'>il telefono deve essere composto da 10 numeri</h3>";
        else if(!$maiuscoloPW || !$numeriPW || strlen($psw) < 8 || !$specialePW)
            echo "<h3 class='comment'>la password deve essere composta da almeno 8 caratteri tra cui una maiuscola un numero e un carattere speciale</h3>";
        else if($repsw <> $psw)
            echo "<h3 class='comment'>Le due password non corrispondono</h3>";
        else if($captcha <> $_POST['captcha'])
            echo "<h3 class='comment'>Completare il captcha</h3>";
        else { //se è tutto corretto
            //collegamento al server che contiene il database
            $database = mysql_connect("localhost", "root", "")
            or die ("Errore di connessione al database");
            //collegamento al database covid
            mysql_select_db("esame2")
            or die ("Errore connessione con database");
            //stringa query
            $verifica = "SELECT email, codiceFiscale, telefono FROM utenti WHERE email='$email' OR codiceFiscale='$codiceFiscale' OR telefono = '$telefono'";
            //si effettua la query
            $query = mysql_query($verifica);
            $righe = mysql_num_rows($query);
            //se esiste un campo vuol dire che è gia presente un utente con quei dati
            if($righe != 0) {
                echo "<h3 class='comment'>Utente già esistente se sei gia registrato effettua il log <a href='login.html'>qui</a></h3>";
            } else {    //altrimenti si registra l'utente nella sessioneRegistrazione
                $_SESSION['cognome'] = $cognome; 
                $_SESSION['nome'] = $nome; 
                $_SESSION['email'] = $email; 
                $_SESSION['telefono'] = $telefono; 
                $_SESSION['codiceFiscale'] = $codiceFiscale; 
                $_SESSION['psw'] = $psw;
                $_SESSION['pass'] = true; 
                $_SESSION['admin'] = false; 
                    
                header('Location: sessioneRegistrazione.php');
            }
            mysql_close();
        }
    }    
?>
