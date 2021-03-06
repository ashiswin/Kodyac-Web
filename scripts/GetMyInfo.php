<?php
	function prettyRace($r) {
		if(strcmp($r, "CN") == 0) {
			return "CHINESE";
		}
		else if(strcmp($r, "EU") == 0) {
			return "EURASIAN";
		}
		else if(strcmp($r, "IN") == 0) {
			return "INDIAN";
		}
		else if(strcmp($r, "ML") == 0) {
			return "MALAY";
		}
	}
	
	function prettySex($s) {
		if(strcmp($s, "F") == 0) {
			return "FEMALE";
		}
		else if(strcmp($s, "M") == 0) {
			return "MALE";
		}
	}
	
	function prettyNationality($n) {
		$handle = fopen("utils/alpha-country-codes.txt", "r");
		if ($handle) {
			while (($line = fgets($handle)) !== false) {
				$arr = explode(",", $line);
				if(strcmp(trim($arr[1]), $n) == 0) {
					fclose($handle);
					return strtoupper($arr[0]);
				}
			}

			fclose($handle);
		} else {
			// error opening the file.
		}
	}
	
	$nric = $_GET['nric'];
	
	// TODO: Check for valid NRIC
	
	$s = curl_init(); 

	curl_setopt($s, CURLOPT_URL, "https://myinfo.api.gov.sg/dev/L0/v1/person/" . $nric . "/");
	curl_setopt($s, CURLOPT_RETURNTRANSFER, true); 
	
	$result = json_decode(curl_exec($s));
	
	$response["success"] = true;
	$response["details"]["name"] = $result->name->value;
	$response["details"]["sex"] = prettySex($result->sex->value);
	$response["details"]["race"] = prettyRace($result->race->value);
	$response["details"]["dob"] = $result->dob->value;
	$response["details"]["nationality"] = prettyNationality($result->nationality->value);
	$response["details"]["address"] = $result->regadd->block . " " . $result->regadd->street . " #" . $result->regadd->floor . "-" . $result->regadd->unit . ", S" . $result->regadd->postal;
	
	echo(json_encode($response));
?>
