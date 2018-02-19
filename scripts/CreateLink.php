<?php
	require_once 'utils/database.php';
	require_once 'connectors/LinkConnector.php';
	require_once 'connectors/APIKeyConnector.php';
	
	$apiKey = $_POST['apiKey'];
	
	$LinkConnector = new LinkConnector($conn);
	$APIKeyConnector = new APIKeyConnector($conn);
	
	$apiKeyEntry = $APIKeyConnector->selectByKey($apiKey);
	
	if(!$apiKeyEntry || $apiKeyEntry[APIKeyConnector::$COLUMN_ISDELETED] == 1) {
		$response['success'] = false;
		$response['message'] = "Invalid API key used!";
	}
	else if(!$LinkConnector->create($apiKeyEntry[APIKeyConnector::$COLUMN_COMPANYID], $apiKey)) {
		$response['success'] = false;
		$response['message'] = "Failed to create link!";
	}
	else {
		$linkId = $conn->insert_id;
		$APIKeyConnector->addRequest($apiKeyEntry[APIKeyConnector::$COLUMN_ID]);
		$response['success'] = true;
		$response['link'] = "http://www.kodyac.tech/links/kyc.php?id=" . $linkId;
	}

	echo(json_encode($response));
?>
