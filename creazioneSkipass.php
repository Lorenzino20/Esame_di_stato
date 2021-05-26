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
                <li><a href="monitoraggio_impianti.php">Monitoraggio impianti</a></li>
                <li><a href="modifica_utente.php">Info utente</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
    <div class="area_cliente">
<?php
    session_start();
    if(!isset($_SESSION['pass'])) {
        header("Location: error.html");
        exit();
    } else {
        $codice_fiscale = $_SESSION['codice_fiscale'];
        $id_utente = $_SESSION['id_utente'];
        if(isset($_POST['piano'])) {
            $p = $_POST['piano'];
        }
    
        $database = mysql_connect("localhost", "root", "")
            or die("Errore di connessione al database");
    
        mysql_select_db("esame2")
            or die ("Errore connessione con database");

        $testo = "SELECT * FROM skipass WHERE id_utente = '$id_utente'";

        $query = mysql_query($testo);
        $righe = mysql_num_rows($query);

        if($righe <> 0) {
            echo "<h1>Skipass già esistente per accedere all'area clienti clicca <a href='utente.php'>qui</a></h1>";
        } else {
            $oggi = date ("Y/m/d");
            $due_mesi = strtotime('+60 day', strtotime($oggi));
            $due_mesi = date('Y/m/d', $due_mesi);
            $testo = "INSERT INTO skipass(risalite_rimanenti, data_attivazione, data_scadenza, id_utente, risalite_piano) VALUES ('$p', '$oggi', '$due_mesi', '$id_utente', '$p')";
            $query = mysql_query($testo);

            if($query) {
                header('Location: utente.php');
            } else {
                echo "<h1>Errore nella generazione dello skipass. Clicca <a href='utente.php'>qui</a> per tornare nella sezione di acquisto</h1>";
            }
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