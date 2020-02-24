<?php

//*************************************************************
//* This is a version for Mikro-Aitta connection.
//*************************************************************
	//*************************************************************
	//* Connection preparation
	//*************************************************************

	$servername = "localhost:3306";
	$uname = "omnia";
	$pword = "A7t#Jg6M";
	$dbname = "tstpriceupdate";
	$conn = @mysqli_connect($servername, $uname, $pword, $dbname);

	// If connection not success en
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
