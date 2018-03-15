<?php
	$nric = $_GET['nric'];
	
	$s = curl_init(); 

	curl_setopt($s, CURLOPT_URL, $request);
	curl_setopt($s, CURLOPT_HEADER, TRUE);
	curl_setopt($s, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($s, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($s, CURLOPT_RETURNTRANSFER, true); 
	curl_setopt($s, CURLOPT_POSTFIELDS, '{"url": "https://myinfo.api.gov.sg/dev/L0/v1/person/' . $nric .'/"}');
	
	$result = curl_exec($s);
	
	echo $result;
?>
