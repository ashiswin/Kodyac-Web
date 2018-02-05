<?php
	require_once 'utils/database.php';
	require_once 'connectors/LinkConnector.php';

	$companyId = $_POST['companyId'];

	$LinkConnector = new LinkConnector($conn);

	if(!$LinkConnector->create($companyId)) {
		$response['success'] = false;
		$response['message'] = "Failed to create link!";
	}
	else {
		$response['success'] = true;
	}

	echo(json_encode($response));
?>