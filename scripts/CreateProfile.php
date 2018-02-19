<?php
	require_once 'utils/database.php';
	require_once 'connectors/ProfileConnector.php';

	$linkId = $_POST['linkId'];
	$name = $_POST['name'];
	$address = $_POST['address'];
	$nric = $_POST['nric'];
	$contact = $_POST['contact'];
	$nationality = $_POST['nationality'];
	$dob = $_POST['dob'];

	$ProfileConnector = new ProfileConnector($conn);
	
	// TODO: Check if linkId exists
	
	if(!$ProfileConnector->create($linkId, $name, $address, $nric, $contact, $nationality, $dob)) {
		$response['success'] = false;
		$response['message'] = "Failed to create profile!";
	}
	else {
		$response['success'] = true;
	}

	echo(json_encode($response));
?>
