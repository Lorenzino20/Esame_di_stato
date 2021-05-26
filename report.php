<?php
    require('C:\xampp\htdocs\pdf\fpdf.php');
    class PDF extends FPDF {
        function connect() {
            $database = mysql_connect("localhost", "root", "")
            or die ("Errore di connessione al database");

            mysql_select_db("esame2")
            or die ("Errore connessione con database");

            $testo = "SELECT * FROM prenota ORDER BY data";
            $query = mysql_query($testo);
            $this -> table();
            while($righe = mysql_fetch_array($query)) {
                $this -> data($righe['id_tessera'], $righe['id_risalita'], $righe['orario'], $righe['data']);
            }

            $this -> Ln(20);
            $this -> table1();

            $testo = "SELECT prenota.id_tessera, COUNT(prenota.id_tessera) AS conteggio FROM prenota GROUP BY prenota.id_tessera";
            $query = mysql_query($testo);
            while($righe = mysql_fetch_array($query)) {
                $this -> data1($righe['id_tessera'], $righe['conteggio']);
            }

            $this -> Ln(20);
            $this -> table2();

            $testo = "SELECT prenota.data, prenota.id_tessera, COUNT(prenota.id_tessera) AS conteggio FROM prenota GROUP BY prenota.orario, prenota.data ORDER BY prenota.data ASC";
            $query = mysql_query($testo);
            while($righe = mysql_fetch_array($query)) {
                $this -> data2($righe['id_tessera'], $righe['conteggio'], $righe['data']);
            }

        }

        function table() {
            $this -> Cell(30, 10, "ID TESSERA", 1, 0,'C');
            $this -> Cell(30, 10, "ID RISALITA", 1, 0,'C');
            $this -> Cell(30, 10, "ORARIO", 1, 0,'C');
            $this -> Cell(30, 10, "DATA", 1, 0,'C');
            $this -> Ln(10);
        }

        function table1() {
            $this -> Cell(30, 10, "ID TESSERA", 1, 0,'C');
            $this -> Cell(30, 10, "CONTEGGIO", 1, 0,'C');
            $this -> Ln(10);
        }

        function table2() {
            $this -> Cell(30, 10, "ID TESSERA", 1, 0,'C');
            $this -> Cell(30, 10, "CONTEGGIO", 1, 0,'C');
            $this -> Cell(30, 10, "DATA", 1, 0,'C');
            $this -> Ln(10);
        }

        function data($id_tessera, $id_risalita, $orario, $data) {
            $this -> Cell(30, 10, "$id_tessera", 1, 0, 'C');
            $this -> Cell(30, 10, "$id_risalita", 1, 0, 'C');
            $this -> Cell(30, 10, "$orario", 1, 0, 'C');
            $this -> Cell(30, 10, "$data", 1, 0, 'C');
            $this -> Ln(10);
        }

        function data1($id_tessera, $conteggio) {
            $this -> Cell(30, 10, "$id_tessera", 1, 0, 'C');
            $this -> Cell(30, 10, "$conteggio", 1, 0, 'C');
            $this -> Ln(10);
        }

        function data2($id_tessera, $conteggio, $data) {
            $this -> Cell(30, 10, "$id_tessera", 1, 0, 'C');
            $this -> Cell(30, 10, "$conteggio", 1, 0, 'C');
            $this -> Cell(30, 10, "$data", 1, 0, 'C');
            $this -> Ln(10);
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