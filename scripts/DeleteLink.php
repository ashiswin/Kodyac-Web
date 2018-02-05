<?php
	require_once 'utils/database.php';
	require_once 'connectors/LinkConnector.php';

	$id = $_POST['id'];

	$LinkConnector = new LinkConnector($conn);

	$response['link'] = $LinkConnector->delete($id);
	$response['success'] = true;

	echo(json_encode($response));
?>