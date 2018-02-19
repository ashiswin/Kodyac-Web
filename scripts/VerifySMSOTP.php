<?php
	include "utils/database.php";
	include "connectors/OTPConnector.php";
	
	$otpString = $_POST['otp'];
	$linkid = $_POST['linkId'];
	
	$OTPConnector = new OTPConnector($conn);
	$otp = $OTPConnector->select($linkid);
	
	$dbdate = strtotime($otp[OTPConnector::$COLUMN_CREATEDON]);
	if (time() - $dbdate > 5 * 60) {
		$response["success"] = false;
		$response["message"] = "OTP expired after " . (time() - $dbdate) . "s";
	}
	else if(strcmp($otp[OTPConnector::$COLUMN_OTP], $otpString) == 0) {
		$response["success"] = true;
	}
	else {
		$response["success"] = false;
		$response["message"] = "OTP incorrect";
	}
	
	echo(json_encode($response));
?>
