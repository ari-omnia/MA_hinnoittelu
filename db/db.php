<?php

	//*************************************************************
	//* Connection preparation
	//*************************************************************

	//$servername = "localhost:50205";
	//echo var_dump($_SERVER);

	$servername = "localhost:3306";
	$uname = "root";  // tämä on tietokannan käyttäjä, ei tekemäsi järjestelmän
	$pword = "root";
	$dbname = "yhdistys";
	$conn = @mysqli_connect($servername, $uname, $pword, $dbname);

	if(!$conn) {
		$uname = "root";
		$pword = "";
		$conn = mysqli_connect($servername, $uname, $pword, $dbname);
	}
	$conn = @mysqli_connect($servername, $uname, $pword, $dbname);

	if(!$conn) {
		$servername = "localhost:51548";
		$uname = "azure";
		$pword = "6#vWHD_$";
		//$uname = "";
		//$pword = "";
		$conn = @mysqli_connect($servername, $uname, $pword, $dbname);
	}
	if(!$conn) {
		$servername = "localhost:50205";
		$uname = "root";
		$pword = "";
		$conn = @mysqli_connect($servername, $uname, $pword, $dbname);
	}
	// jos yhteyden muodostaminen ei onnistunut, keskeytä
	// Check connection
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
		echo "Connetion failed: " . print_r($conn);
	}
	//echo "Connection ok: " . print_r($conn);
	//if ($yhteys->connect_error) {
	//   die("Yhteyden muodostaminen epäonnistui: " . $yhteys->connect_error);
	//}
	// aseta merkistökoodaus (muuten ääkköset sekoavat)
	$conn->set_charset("utf8");
