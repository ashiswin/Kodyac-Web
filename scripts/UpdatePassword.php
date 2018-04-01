<?php
	require_once 'utils/database.php';
	require_once 'connectors/CompanyConnector.php';
	require_once 'utils/random_gen.php';

	$companyId = $_POST['companyId'];
	$password = trim($_POST['password']);
	$newPassord = trim($_POST['newPassword']);
	
	$CompanyConnector = new CompanyConnector($conn);
	
	$result = $CompanyConnector->select($companyId);
	if(!$result) {
		$response["message"] = "Invalid company ID";
		$response["success"] = false;
	}
	else {
		$passwordHash = hash('sha512', ($password . $result[CompanyConnector::$COLUMN_SALT]));
		if(strcmp($passwordHash, $result[CompanyConnector::$COLUMN_PASSWORDHASH]) == 0) {
			$salt = random_str(10);
			$newPasswordHash = hash('sha512', ($newPassword . $salt));
			$CompanyConnector->updatePassword($companyId, $newPassword, $salt);
			$response["success"] = true;
		}
		else {
			$response["success"] = false;
			$response["message"] = "Password update failed";
		}
	}
	
	echo(json_encode($response));
?>
