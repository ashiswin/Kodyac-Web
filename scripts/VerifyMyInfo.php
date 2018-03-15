<?php
	include "utils/database.php";
	include "connectors/ProfileConnector.php";
	
	$linkid = $_POST['linkId'];
	$name = $_POST['name'];
	$address = $_POST['address'];
	$nationality = $_POST['nationality'];
	$dob = $_POST['dob'];
	$nric = $_POST['nric'];
	$sex = $_POST['sex'];
	$race = $_POST['race'];
	
	$ProfileConnector = new ProfileConnector($conn);
	
	$ProfileConnector->updateOtherInfo($linkid, $name, $address, $nric, $nationality, $dob, $sex, $race);
	
	$response["success"] = true;
	echo(json_encode($response));
?>
