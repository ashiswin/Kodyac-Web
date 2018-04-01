<?php
	require_once "utils/database.php";
	require_once "connectors/CompanyConnector.php";
	
	$companyId = $_POST['companyId'];
	$callback = $_POST['callback'];
	
	$CompanyConnector = new CompanyConnector($conn);
	
	$CompanyConnector->updateCallback($companyId, $callback);
	
	$response["success"] = true;
	
	echo(json_encode($response));
?>
