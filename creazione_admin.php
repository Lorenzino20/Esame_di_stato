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
                <li class="current"><a href="creazione_admin.php">Crea un utente</a></li>
            </ul>
        </div>
    </div>
    <div class="main">
    <div class="area_admin">
        <div class="comment">
                <h1>Crea un utente IperSky</h1>
        </div>
        <div class="creazione_skipass_admin">
            <form action="sessione_creazione_admin.php" method="POST">
                <div id="item">
                    <label for="nome">Nome</label>
                    <input type="text" name="nome" required>
                </div>
                <div id="item">
                    <label for="cognome">Cognome</label>
                    <input type="text" name="cognome" required >
                </div>
                <div id="item">
                    <label for="email">Email</label>
                    <input type="email" name="email" required >
                </div>
                <div id="item">
                    <label for="telefono">Telefono</label>
                    <input type="text" name="telefono" required>
                </div>
                <div id="item">
                    <label for="codiceFiscale">Codice fiscale</label>
                    <input type="text" name="codiceFiscale" required> 
                </div>
                <div id="item">
                    <label for="psw">Password</label>
                    <input type="password" name="psw" >
                </div>
                <label>Admin</label>
                <div id="item_radio">
                    
                    <input type="radio" name="admin" value="0">
                    <label for="admin">No</label>
                    
                    <input type="radio" name="admin" value="1">
                    <label for="admin">Si</label>
                </div>
                <div id="button">
                    <input type="submit" value="Crea utente" name="creazione_utente" class="submit">
                </div>
            </form>
        </div>
<?php
    $database = mysql_connect("localhost", "root", "")
    or die("Errore di connessione al database");

    mysql_select_db("esame2")
    or die ("Errore connessione con database");
    $testo = "SELECT * FROM utente U WHERE U.id_utente NOT IN(SELECT U.id_utente FROM utente U, skipass S WHERE U.id_utente = S.id_utente)";
    $query = mysql_query($testo);
    $riga = mysql_num_rows($query);
    if($riga <> 0) {
        echo "<div class='tabella_utenti_admin'>
        <table class='utenti_admin'><tr><td>Tabella utenti che non hanno uno skipass</td></tr>
        <tr>
        <td>Id utente</td>
        <td>Codice fiscale</td>
        <td>Nome</td>
        <td>Cognome</td>
        <td>Email</td>
        <td>Telefono</td>
        </tr>";
        while($righe = mysql_fetch_array($query)) {
            if($righe['admin'] <> 1) {
                echo "<tr>
                <td>$righe[id_utente]</td>
                <td>$righe[codice_fiscale]</td>
                <td>$righe[nome]</td>
                <td>$righe[cognome]</td>
                <td>$righe[email]</td>
                <td>$righe[telefono]</td>
                </tr>";
            }
        }
        echo "</table></div>";

        echo "<div class='comment'>
        <h1>Crea uno skipass</h1>
        </div>
        <div class='piani'>
            <form action='sessione_creazione_admin.php' method='POST'>
                <h1>Inserisci un codice fiscale:</h1>
                <input type='text' id='utente' name='codiceFiscale' >
                <h1>Seleziona un piano:</h1>
                <h2>Piano 1</h2>
                <p>Numero di risalite: 5</p>
                <input type='radio' id='piano' name='piano' value='5'>
                <h2>Piano 2</h2>
                <p>Numero di risalite: 10</p>
                <input type='radio' id='piano' name='piano' value='10'>
                <h2>Piano 3</h2>
                <p>Numero di risalite: 15</p>
                <input type='radio' id='piano' name='piano' value='15'><br/>
                <input id='submit_admin' type='submit' name='creazione_skipass' value='Creazione skipass'>
            </form>
        </div>";
    }
    $testo = "SELECT * FROM impianto_risalita";
    $query = mysql_query($testo);
    $riga = mysql_num_rows($query);
    if($riga <> 0) {
        echo "<div class='tabella_utenti_admin'>
        <table class='utenti_admin'><tr><td>Tabella impianti di risalita esistenti</td></tr>
        <tr>
        <td>Id impiano</td>
        <td>Nome</td>
        <td>Descrizione</td>
        <td>Posti totali</td>
        <td>Orario apertura</td>
        <td>Orario chiusura</td>
        </tr>";
        while($righe = mysql_fetch_array($query)) {    
            echo "<tr>
            <td>$righe[id_risalita]</td>
            <td>$righe[nome]</td>
            <td>$righe[descrizione]</td>
            <td>$righe[posti_totali]</td>
            <td>$righe[orario_apertura]</td>
            <td>$righe[orario_chiusura]</td>
            </tr>";   
        }
        echo "</table></div>";

        echo "<div class='comment'>
        <h1>Aggiungi un impianto</h1>
        </div>
        <div class='creazione_skipass_admin'>
        <form action='sessione_creazione_admin.php' method='POST'>
            <div id='item'>
                <label for='nome'>Nome impianto</label>
                <input type='text' name='nome_impianto' required>
            </div>
            <div id='item'>
                <label for='descrizione'>Descrizione</label>
                <input type='text' name='descrizione' required >
            </div>
            <div id='item'>
                <label for='posti_totali'>Posti totali</label>
                <input type='text' name='posti_totali' required >
            </div>
            <div id='item'>
                <label for='orario_apertura'>Orario apertura</label>
                <input type='text' name='orario_apertura' required>
            </div>
            <div id='item'>
                <label for='orario_chiusura'>Orario chiusura</label>
                <input type='text' name='orario_chiusura' required> 
            </div>
            <div id='button'>
                <input type='submit' value='Aggiungi impianto' name='creazione_impianto' class='submit'>
            </div>
        </form>
        </div>";
    }
?>
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
</body>
</html>
    
       