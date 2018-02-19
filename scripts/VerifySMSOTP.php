<?php
	include "utils/database.php";
	include "connectors/OTPConnector.php";
	
	$otpString = $_POST['otp'];
	$linkid = $_POST['linkId'];
	
	$OTPConnector = new OTPConnector($conn);
	$otp = $OTPConnector->select($otpString);
	
	$dbdate = strtotime($otp[OTPConnector::$COLUMN_CREATEDON]);
	if(strtotime("now") - $dbdate > 5 * 60) {
		$response["success"] = false;
		$response["message"] = "OTP expired after " . (time() - $dbdate) . "s";
	}
	else if($otp[OTPConnector::$COLUMN_USED] == 1) {
		$response["success"] = false;
		$response["message"] = "OTP has already been used";
	}
	else if($linkid == $otp[OTPConnector::$COLUMN_LINKID]) {
		$response["success"] = true;
		$OTPConnector->setUsed($otpString);
	}
	else {
		$response["success"] = false;
		$response["message"] = "OTP incorrect";
	}
	
	echo(json_encode($response));
?>
