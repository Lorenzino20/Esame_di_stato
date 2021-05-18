<?php
    session_start();
    $_SESSION = array();
    print_r($_SESSION);
    header("Location: index.html");
?>