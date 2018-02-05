<?php
	require_once 'utils/database.php';
	require_once 'connectors/CompanyConnector.php';
	require_once 'utils/random_gen.php';
	
	$name = $_POST['name'];
	$username = $_POST['username'];
	$password = $_POST['password'];
	$pocName = $_POST['pocName'];
	$pocEmail = $_POST['pocEmail'];
	$pocContactNumber = $_POST['pocContactNumber'];
	$methods = $_POST['methods'];
	
	$salt = random_str(10);
	$passwordHash = hash('sha512', ($password . $salt));
	
	$CompanyConnector = new CompanyConnector($conn);

	if(!$CompanyConnector->create($name, $username, $passwordHash, $salt, $pocName, $pocEmail, $pocContactNumber, $methods)) {
		$response['success'] = false;
		$response['message'] = "Failed to create company!";
	}
	else {
		$response['success'] = true;
		$response['companyId'] = $conn->insert_id;
	}

	echo(json_encode($response));
?>
