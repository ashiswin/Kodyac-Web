<?php
	$nric = $_GET['nric'];
	
	$s = curl_init(); 

	curl_setopt($s, CURLOPT_URL, "https://myinfo.api.gov.sg/dev/L0/v1/person/" . $nric . "/");
	curl_setopt($s, CURLOPT_RETURNTRANSFER, true); 
	
	$result = json_decode(curl_exec($s));
	
	$response["success"] = true;
	$response["details"]["name"] = $result->name->value;
	$response["details"]["sex"] = $result->sex->value;
	$response["details"]["race"] = $result->race->value;
	$response["details"]["dob"] = $result->dob->value;
	$response["details"]["address"] = $result->regadd->value;
	
	echo(json_encode($response));
?>
