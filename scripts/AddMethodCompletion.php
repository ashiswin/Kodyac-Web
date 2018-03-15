<?php
	include "utils/database.php";
	include "connectors/LinkConnector.php";
	
	$method = $_POST['method'];
	$linkid = $_POST['linkId'];
	
	$LinkConnector = new LinkConnector($conn);
	$link = $LinkConnector->select($linkid);
	
	if(!$link) {
		$response["success"] = false;
		$response["message"] = "Invalid Link ID provided";
		
		echo(json_encode($response));
		return;
	}
	
	$methods = $link[LinkConnector::$COLUMN_COMPLETEDMETHODS];
	
	if(strpos($methods, $method) === false) {
		if($link[LinkConnector::$COLUMN_COMPLETEDMETHODS] == null || strlen($link[LinkConnector::$COLUMN_COMPLETEDMETHODS]) == 0) {
			$methods = $method;
		}
		else {
			$methods .= "|" . $method;
		}
	
		$LinkConnector->setCompletedMethods($linkid, $methods);
	}
	
	$response["success"] = true;
	
	echo(json_encode($response));
?>
