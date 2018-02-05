<?php
	require_once 'utils/database.php';
	require_once 'connectors/CompanyConnector.php';

	$id = $_POST['id'];

	$CompanyConnector = new CompanyConnector($conn);

	$response['company'] = $CompanyConnector->delete($id);
	$response['success'] = true;

	echo(json_encode($response));
?>