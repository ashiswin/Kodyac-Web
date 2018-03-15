<?php
	function prettyRace($r) {
		if(strcmp($r, "CN") == 0) {
			return "Chinese";
		}
		else if(strcmp($r, "EU") == 0) {
			return "Eurasian";
		}
		else if(strcmp($r, "IN") == 0) {
			return "Indian";
		}
		else if(strcmp($r, "ML") == 0) {
			return "Malay";
		}
	}
	
	$nric = $_GET['nric'];
	
	$s = curl_init(); 

	curl_setopt($s, CURLOPT_URL, "https://myinfo.api.gov.sg/dev/L0/v1/person/" . $nric . "/");
	curl_setopt($s, CURLOPT_RETURNTRANSFER, true); 
	
	$result = json_decode(curl_exec($s));
	var_dump($result);
	$response["success"] = true;
	$response["details"]["name"] = $result->name->value;
	$response["details"]["sex"] = $result->sex->value;
	$response["details"]["race"] = prettyRace($result->race->value);
	$response["details"]["dob"] = $result->dob->value;
	$response["details"]["address"] = $result->regadd->value;
	
	echo(json_encode($response));
?>
