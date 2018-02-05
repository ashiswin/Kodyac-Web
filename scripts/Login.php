<?php
	require_once 'utils/database.php';
	require_once 'connectors/CompanyConnector.php';

	$username = trim($_POST['username']);
	$password = trim($_POST['password']);

	$CompanyConnector = new CompanyConnector($conn);

	$result = $CompanyConnector->selectByUsername($username);
	if(!$result) {
		$response["message"] = "Invalid username or password 1";
		$response["success"] = false;
	}
	else {
		$passwordHash = hash('sha512', ($password . $result[CompanyConnector::$COLUMN_SALT]));
		if(strcmp($passwordHash, $result[CompanyConnector::$COLUMN_PASSWORDHASH]) == 0) {
			$response["success"] = true;
			$response["companyId"] = $result[CompanyConnector::$COLUMN_ID];
		}
		else {
			$response["success"] = false;
			$response["message"] = "Invalid username or password";
		}
	}
	
	echo(json_encode($response));
?>
