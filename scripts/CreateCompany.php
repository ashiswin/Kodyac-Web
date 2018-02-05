<?php
	require_once 'utils/database.php';
	require_once 'connectors/CompanyConnector.php';

	$name = $_POST['name'];
	$username = $_POST['username'];
	$passwordHash = $_POST['passwordHash'];
	$salt = $_POST['salt'];
	$pocName = $_POST['pocName'];
	$pocEmail = $_POST['pocEmail'];
	$pocContactNumber = $_POST['pocContactNumber'];
	$methods = $_POST['methods'];

	$CompanyConnector = new CompanyConnector($conn);

	if(!$CompanyConnector->create($name, $username, $passwordHash, $salt, $pocName, $pocEmail, $pocContactNumber, $methods)) {
		$response['success'] = false;
		$response['message'] = "Failed to create company!";
	}
	else {
		$response['success'] = true;
	}

	echo(json_encode($response));
?>