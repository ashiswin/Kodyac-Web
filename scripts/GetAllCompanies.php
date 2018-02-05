<?php
	require_once 'utils/database.php';
	require_once 'connectors/CompanyConnector.php';

	$CompanyConnector = new CompanyConnector($conn);

	$response['companies'] = $CompanyConnector->selectAll();
	$response['success'] = true;

	echo(json_encode($response));
?>