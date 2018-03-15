<?php
	include "utils/database.php";
	include "connectors/ProfileConnector.php";
	
	$linkid = $_POST['linkId'];
	$name = $_POST['name'];
	$address = $_POST['address'];
	$nationality = $_POST['nationality'];
	$dob = $_POST['dob'];
	$nric = $_POST['nric'];
	
	$ProfileConnector = new ProfileConnector($conn);
	
	$ProfileConnector->updateOtherInfo($linkid, $name, $address, $nric, $nationality, $dob);
	
	$response["success"] = true;
	echo(json_encode($response));
?>
