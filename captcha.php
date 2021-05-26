<?php
    session_start();

    $image = imagecreatetruecolor(200, 50); //dimensioni dell'immagine
    //                          width,height
    $background = imagecolorallocate($image, 167, 248, 201);    //colore di sfondo dell'immagine
    //                             immagine, red, green, blue
    imagefill($image, 0, 0, $background); //aggiungere lo sfondo all'immagine  
    //       immagine, x, y, color 

    $linesColor = imagecolorallocate($image, 40, 40, 40); //impostare il colore delle barre
    //                             immagine, red, green, blue
    for ($i=1; $i<=9; $i++) {
    imagesetthickness($image, rand(0,1));	//applicare uno spessore alle barre
    //              immagine, thikness
    imageline($image, 0, $i*5, 200, $i*5, $linesColor); //aggiungere le barre all'immagine
    //     immagine,  x1, y1,  x2,   y2,   colore 
    }

    $captcha = '';
    $textColor = imagecolorallocate($image, 0, 0, 0); //impostare il colore dei numeri
    //                            immagine, red, green, blue
    for ($x = 10; $x <= 200; $x =$x + 20) {
        $value = rand(0, 9); //generare un numero casuale 
        imagechar($image, rand(8, 9), $x, rand(2, 25), $value, $textColor); //posizionare il numero all'interno dell'immagine
        //        immagine, font,     x,     y,        numero,  colore 
        $captcha .= $value; //concatenare il valore del numero agli altri numeri
    }

    $_SESSION['captcha'] = $captcha; 

    header('Content-type: image/png');
    imagepng($image);
    imagedestroy($image);
?>