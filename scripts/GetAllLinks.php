<?php
	require_once 'utils/database.php';
	require_once 'connectors/LinkConnector.php';

	$LinkConnector = new LinkConnector($conn);

	$response['links'] = $LinkConnector->selectAll();
	$response['success'] = true;

	echo(json_encode($response));
?>