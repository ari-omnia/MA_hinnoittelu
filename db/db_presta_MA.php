<?php
    $servername = "localhost:3306";
    $uname = "omnia";
	$pword = "A7t#Jg6M";
    $dbname = "sbox";
    $prestaconn = @mysqli_connect($servername, $uname, $pword, $dbname);

    if (!$prestaconn) {
            die("Connection failed: " . mysqli_connect_error());
            echo "Connetion failed: " . print_r($prestaconn);
    }

    $prestaconn->set_charset("utf8");
