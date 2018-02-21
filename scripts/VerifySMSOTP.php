<?php
	include "utils/database.php";
	include "connectors/OTPConnector.php";
	include "connectors/ProfileConnector.php";
	
	$otpString = $_POST['otp'];
	$linkid = $_POST['linkId'];
	
	$OTPConnector = new OTPConnector($conn);
	$ProfileConnector = new ProfileConnector($conn);
	
	$otp = $OTPConnector->select($otpString);
	
	$dbdate = strtotime($otp[OTPConnector::$COLUMN_CREATEDON]);
	if(strtotime("now") - $dbdate > 5 * 60) {
		$response["success"] = false;
		$response["message"] = "This OTP has expired. Please request a new one.";
	}
	else if($otp[OTPConnector::$COLUMN_USED] == 1) {
		$response["success"] = false;
		$response["message"] = "This OTP has already been used.";
	}
	else if($linkid == $otp[OTPConnector::$COLUMN_LINKID]) {
		$response["success"] = true;
		$response["number"] = $otp[OTPConnector::$COLUMN_NUMBER];
		
		$OTPConnector->setUsed($otpString);
		$ProfileConnector->updateContact($linkid, $otp[OTPConnector::$COLUMN_NUMBER]);
	}
	else {
		$response["success"] = false;
		$response["message"] = "This OTP is invalid.";
	}
	
	echo(json_encode($response));
?>
