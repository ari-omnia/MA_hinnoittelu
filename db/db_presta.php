<?php
    $servername = "localhost:3306";
    $uname = "root";  // tämä on tietokannan käyttäjä, ei tekemäsi järjestelmän
    $pword = "";
    $dbname = "test";
    $prestaconn = @mysqli_connect($servername, $uname, $pword, $dbname);

    if (!$prestaconn) {
            die("Connection failed: " . mysqli_connect_error());
            echo "Connetion failed: " . print_r($prestaconn);
    }

    $prestaconn->set_charset("utf8");
        
