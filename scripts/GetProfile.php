<?php
	require_once 'utils/database.php';
	require_once 'connectors/ProfileConnector.php';

	$id = $_GET['id'];

	$ProfileConnector = new ProfileConnector($conn);

	$response['profile'] = $ProfileConnector->select($id);
	$response['success'] = true;

	echo(json_encode($response));
?>