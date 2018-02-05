<?php
	require_once 'utils/database.php';
	require_once 'connectors/CompanyConnector.php';

	$id = $_GET['id'];

	$CompanyConnector = new CompanyConnector($conn);

	$response['company'] = $CompanyConnector->select($id);
	$response['success'] = true;

	echo(json_encode($response));
?>