<?php
	$conn = new mysqli("devostrum.no-ip.info", "kodyac", "kodyac", "kodyac");
	if($conn->connect_error) {
		$response["success"] = false;
		$response["message"] = "Connection failed: " . $conn->connect_error;
	}
?>
