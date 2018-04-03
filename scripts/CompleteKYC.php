<?php
	include "utils/database.php";
	include "connectors/LinkConnector.php";
	
	$linkId = $_POST['linkId'];
	
	$LinkConnector = new LinkConnector($conn);
	$LinkConnector->setStatus($linkId, "completed");
	
	$response["success"] = true;
	
	echo(json_encode($response));
?>
