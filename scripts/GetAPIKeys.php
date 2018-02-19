<?php
	require_once 'utils/database.php';
	require_once 'connectors/APIKeyConnector.php';

	$companyId = $_GET['companyId'];

	$APIKeyConnector = new APIKeyConnector($conn);

	$response['keys'] = $APIKeyConnector->selectByCompany($companyId);
	$response['success'] = true;

	echo(json_encode($response));
?>
