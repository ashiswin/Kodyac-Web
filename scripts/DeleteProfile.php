<?php
	require_once 'utils/database.php';
	require_once 'connectors/ProfileConnector.php';

	$id = $_POST['id'];

	$ProfileConnector = new ProfileConnector($conn);

	$response['profile'] = $ProfileConnector->delete($id);
	$response['success'] = true;

	echo(json_encode($response));
?>