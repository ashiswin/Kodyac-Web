<?php
	include "utils/database.php";
	include "connectors/LinkConnector.php";
	include "connectors/CompanyConnector.php";
	require_once "utils/websocket.php";
	
	$linkId = $_POST['id'];
	
	$LinkConnector = new LinkConnector($conn);
	$LinkConnector->setStatus($linkId, "completed");
	$LinkConnector->setCompleted($linkId, date('Y-m-d H:i:s'));
	$link = $LinkConnector->select($linkId);
	
	$CompanyConnector = new CompanyConnector($conn);
	$company = $CompanyConnector->select($link[LinkConnector::$COLUMN_COMPANYID]);
	
	// Update stats server
	$ws = new ws(array
	(
		'host' => 'devostrum.no-ip.info',
		'port' => 8080,
		'path' => ''
	));
	$result = $ws->send("update|https://www.kodyac.tech/links/kyc.php?id=" . $linkId);
	$ws->close();
	
	$response["success"] = true;
	
	echo(json_encode($response));
?>
