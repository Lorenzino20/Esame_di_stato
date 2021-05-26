<?php
require('C:\xampp\htdocs\pdf\fpdf.php');
session_start();

class PDF extends FPDF {
    function connect() {
        $database = mysql_connect("localhost", "root", "")
            or die("Errore di connessione al database");

        mysql_select_db("esame2")
            or die ("Errore connessione con database");
            
        $codice_fiscale = $_SESSION['codice_fiscale'];
        $testo = "SELECT S.id_tessera AS id_tessera 
        FROM skipass S, utente U
        WHERE S.id_utente = U.id_utente AND U.codice_fiscale = '$codice_fiscale'";
        $query = mysql_query($testo);
        $righe = mysql_fetch_array($query);
        $id_tessera = $righe['id_tessera'];
        $testo = "SELECT P.orario AS orario, P.data AS data 
        FROM prenota P, impianto_risalita I, skipass S, utente U 
        WHERE I.id_risalita = P.id_risalita AND P.id_tessera = S.id_tessera AND S.id_utente = U.id_utente 
        AND U.codice_fiscale = '$codice_fiscale'";
        $query = mysql_query($testo);
        while($righe = mysql_fetch_array($query)) {
            $string = "$righe[data]_$righe[orario]";
            if(isset($_POST[$string]) && $_POST[$string] == 1) {
                $testo1 = "SELECT I.nome AS nome_impianto, P.data AS data, P.orario AS orario,
                P.id_tessera AS id_tessera, P.id_risalita AS id_risalita
                FROM prenota P, impianto_risalita I
                WHERE P.data = '$righe[data]' AND P.orario = '$righe[orario]' AND P.id_tessera = '$id_tessera' AND P.id_risalita = I.id_risalita";
                $query1 = mysql_query($testo1);
                $righe1 = mysql_fetch_array($query1);
                $id_tessera = $righe1['id_tessera'];
                $id_risalita = $righe1['id_risalita'];
                $orario = $righe1['orario'];
                $nome_impianto = $righe1['nome_impianto'];
                $data = $righe1['data'];
                $this -> table($string);
                $this -> data($nome_impianto, $data, $orario);
                $this -> code($id_tessera, $id_risalita, $orario);
            }
        }
    }

    function table($string) {
        $stringa = explode('_', $string);
        $data = $stringa[0];
        $orario = $stringa[1];
        $this -> Text($this->getX(), $this->getY(), "Data:".$data);
        $this -> Ln(10);
        $this -> Text($this->getX(), $this->getY(), "Orario:".$orario);
        $this -> Ln(10);
        $this -> Cell(30, 10, "Impianto", 1, 0,'C');
        $this -> Cell(30, 10, "Data", 1, 0,'C');
        $this -> Cell(30, 10, "Orario", 1, 0,'C');
        $this -> Ln(10);
    }

    function data($nome_impianto, $data, $orario) {
        $this -> Cell(30, 10, "$nome_impianto", 1, 0, 'C');
        $this -> Cell(30, 10, "$data", 1, 0, 'C');
        $this -> Cell(30, 10, "$orario", 1, 0, 'C');
        $this -> Ln(10);
    }

    function code($id_tessera, $id_risalita, $orario) {
        $qrcode = 'http://chart.googleapis.com//chart?cht=qr&chs=150x150&chl=id_tessera:'.$id_tessera.'id_risalita:'.$id_risalita.'orario:'.$orario;
        /* $img = 'Qrcodes/qrcode-id_tessera'.$id_tessera.'-id_risalita'.$id_risalita.'-orario'.$orario.'.png';
        file_put_contents($img, file_get_contents($qrcode)); */
        $this-> Ln(5);
        $this-> Image($qrcode, $this->getX(), $this->getY(), 40, 40, 'png');  
        $this -> Ln(50);
    } 

}
    

$pdf = new PDF();
$pdf -> setAuthor("Lorenzo");
$pdf -> setCreator("Lorenzo");
$pdf -> SetFont("Arial", "", 14);
$pdf -> AddPage();
$pdf -> connect();
$pdf -> Output(); 
?>