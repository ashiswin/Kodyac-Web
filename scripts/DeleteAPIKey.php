<?php
	require_once 'utils/database.php';
	require_once 'connectors/APIKeyConnector.php';

	$id = $_POST['id'];

	$APIKeyConnector = new APIKeyConnector($conn);

	$APIKeyConnector->delete($id);
	$response['success'] = true;

	echo(json_encode($response));
?>
