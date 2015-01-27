<?php
	$dbserver = "db.dcs.aber.ac.uk";
	$dbname = "csgp16_14_15";
	$user = "amdjoh52";
	$pw = "o6f7rAk8";
	
	//Connect to the database
	$dbconnect = new mysqli($dbserver, $user, $pw, $dbname);
	//Check if connected to the database
	if ($dbconnect->connect_error) {
		die("Connection failed: " . $dbconnect->connect_error);
	}
	
	