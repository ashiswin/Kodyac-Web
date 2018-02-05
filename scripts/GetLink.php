<?php
	require_once 'utils/database.php';
	require_once 'connectors/LinkConnector.php';

	$id = $_GET['id'];

	$LinkConnector = new LinkConnector($conn);

	$response['link'] = $LinkConnector->select($id);
	$response['success'] = true;

	echo(json_encode($response));
?>