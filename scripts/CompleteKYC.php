<?php
	include "utils/database.php";
	include "connectors/LinkConnector.php";
	
	$linkId = $_POST['id'];
	
	$LinkConnector = new LinkConnector($conn);
	$LinkConnector->setStatus($linkId, "completed");
	$LinkConnector->setCompleted($linkId, date('Y-m-d H:i:s')
	$response["success"] = true;
	
	echo(json_encode($response));
?>
