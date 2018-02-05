<?php
	require_once 'utils/database.php';
	require_once 'connectors/ProfileConnector.php';

	$ProfileConnector = new ProfileConnector($conn);

	$response['profiles'] = $ProfileConnector->selectAll();
	$response['success'] = true;

	echo(json_encode($response));
?>