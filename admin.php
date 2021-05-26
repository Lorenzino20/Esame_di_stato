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
                <li class="current"><a href="admin.php">Area ADMIN</a></li>
                <li><a href="visualizza_admin.php">Visualizza prenotazioni</a></li>
                <li><a href="creazione_admin.php">Crea un utente</a></li>
            </ul>
        </div>
    </div>
    <div class='area_admin'>
<?php
    session_start();
    if(!isset($_SESSION['admin'])) {
        header("Location: error.html");
        exit();
    } else {
        echo "<div class='admin'>
            <h3>Benvenuto ADMIN</h3>
        </div>";
        echo "<div id='home_admin'>
                <h2 id='item'>Per analizzare, modificare o eliminare dei campi del database recati nella sezione di modifica cliccando qui</h2><a href='visualizza_admin.php'><img src='Immagini/modifca_admin.png'></img></a>
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