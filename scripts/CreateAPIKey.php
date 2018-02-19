<?php
	require_once 'utils/database.php';
	require_once 'utils/random_gen.php';
	require_once 'connectors/APIKeyConnector.php';
	
	$companyId = $_POST['companyId'];
	$name = $_POST['name'];
	
	// TODO: Check if companyId exists
	
	$apiKey = random_str(32);
	$APIKeyConnector = new APIKeyConnector($conn);
	
	if(!$APIKeyConnector->create($companyId, $name, $apiKey)) {
		$response['success'] = false;
		$response['message'] = "Failed to create API key!";
	}
	else {
		$response['success'] = true;
	}

	echo(json_encode($response));
?>
