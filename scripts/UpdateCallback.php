<?php
	require_once "scripts/utils/database.php";
	require_once "scripts/connectors/CompanyConnector.php";
	
	$companyId = $_POST['companyId'];
	$callback = $_POST['callback'];
	
	$CompanyConnector = new CompanyConnector($conn);
	
	$CompanyConnector->updateCallback($companyId, $callback);
	
	$response["success"] = true;
	
	echo(json_encode($response));
?>
