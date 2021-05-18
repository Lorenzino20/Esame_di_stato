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
    <div class="area_cliente">
<?php
    if(!isset($_POST['modifica'])) {
        header("Location: error.html");
    } else {
        session_start();
        $codice_fiscale = $_SESSION['codice_fiscale'];
        $database = mysql_connect("localhost", "root", "")
        or die("Errore di connessione al database");
    
        mysql_select_db("esame2")
        or die ("Errore connessione con database");
    
        $nome = $_POST['nome'];
        $cognome = $_POST['cognome'];
        $telefono = $_POST['telefono'];
        $email = $_POST['email'];

        $testo = "UPDATE utente SET nome = '$nome', cognome = \"$cognome\", telefono = '$telefono', email = '$email' WHERE codice_fiscale = '$codice_fiscale'";
        $query = mysql_query($testo);

        if($query) {
            echo "<h2>Modifica effettuata ora torna all' Area clienti e verifica se le modifiche sono visibili.
            Per accedere all'Area clienti clicca <a href='utente.php'>qui</a></h2>";
        } else {
            echo "<h2>Modifica non effettuata.
            Per accedere all'Area clienti clicca <a href='utente.php'>qui</a></h2>";
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