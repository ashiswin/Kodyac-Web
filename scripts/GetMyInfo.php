<?php
	$nric = $_GET['nric'];
	
	$s = curl_init(); 

	curl_setopt($s, CURLOPT_URL, "https://myinfo.api.gov.sg/dev/L0/v1/person/" . $nric . "/");
	curl_setopt($s, CURLOPT_RETURNTRANSFER, true); 
	
	$result = curl_exec($s);
	
	echo $result;
?>
