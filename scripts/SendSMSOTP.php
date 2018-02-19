<?php
	include "lib/smsGateway.php";
	include "utils/random_gen.php";
	include "utils/database.php";
	include "connectors/OTPConnector.php";
	
	$smsGateway = new SmsGateway('sobhit.me@gmail.com', 'Shobhit1998');
	
	$number = $_POST['number'];
	$linkid = $_POST['linkId'];
	
	$otp = random_str();
	
	$deviceID = 79179;
	$message = '[KodYaC] Your OTP is ' . $otp . '. Please enter it within 5 mins.';

	$options = [
		'send_at' => strtotime('now'),
		'expires_at' => strtotime('+5 minutes') // Cancel the message in 5 minutes if the message is not yet sent
	];

	//Please note options is no required and can be left out
	$result = $smsGateway->sendMessageToNumber($number, $message, $deviceID, $options);
	
	$OTPConnector = new OTPConnector($conn);
	$OTPConnector->create($linkid, $otp);
	
	$response["success"] = true;
	
	echo(json_encode($response));
?>
