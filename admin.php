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
            </ul>
        </div>
    </div>
<?php
    session_start();
    if(!isset($_SESSION['admin'])) {
        header("Location: error.html");
        exit();
    } else {
        echo "<h1>SEZIONE ADMIN</h1>";
        echo "<h2>Per visualizzare tutte le prenotazioni effettuate clicca <a href='visualizza_admin.php'>qui</a></h2>";
    }
?>