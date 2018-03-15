<?php
	include "lib/smsGateway.php";
	include "utils/random_gen.php";
	include "utils/database.php";
	include "connectors/OTPConnector.php";
	require 'lib/twilio-php-master/Twilio/autoload.php';
	use Twilio\Rest\Client;
	
	//$smsGateway = new SmsGateway('sobhit.me@gmail.com', 'Shobhit1998');
	
	$sid = 'AC84348745a8fdc67fd813fa221db659f1';
	$token = 'e4c926a935ae3b3dad221fa6a611afd9';
	$client = new Client($sid, $token);
	
	$number = $_POST['number'];
	$linkid = $_POST['linkId'];
	
	$otp = random_str();
	
	//$deviceID = 79179;
	$message = '[KodYaC] Your OTP is ' . $otp . '. Please enter it within 5 mins.';

	/*$options = [
		'send_at' => strtotime('now'),
		'expires_at' => strtotime('+5 minutes') // Cancel the message in 5 minutes if the message is not yet sent
	];

	$result = $smsGateway->sendMessageToNumber($number, $message, $deviceID, $options);*/
	
	$client->messages->create(
		// the number you'd like to send the message to
		$number,
		array(
		// A Twilio phone number you purchased at twilio.com/console
			'from' => '+1 561-220-2592 ',
			// the body of the text message you'd like to send
			'body' => $message
		)
	);
	
	$OTPConnector = new OTPConnector($conn);
	$OTPConnector->create($linkid, $otp, $number);
	
	$response["success"] = true;
	
	echo(json_encode($response));
?>
