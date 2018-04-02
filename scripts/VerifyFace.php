<?php
	require_once 'utils/random_gen.php'
	// Perform file upload
	$target_dir = "../uploads/";
	function base64_to_jpeg($base64_string, $output_file) {
		// open the output file for writing
		$ifp = fopen( $output_file, 'wb' ); 

		// split the string on commas
		// $data[ 0 ] == "data:image/png;base64"
		// $data[ 1 ] == <actual base64 string>
		$data = explode( ',', $base64_string );

		// we could add validation here with ensuring count( $data ) > 1
		fwrite( $ifp, base64_decode( $data[ 1 ] ) );

		// clean up the file resource
		fclose( $ifp ); 

		return $output_file; 
	}
	$target_file1 = base64_to_jpeg($_POST['face1'], $target_dir . random_str(10));
	$target_file2 = base64_to_jpeg($_POST['face2'], $target_dir . random_str(10));
	
	
	$request = 'https://westcentralus.api.cognitive.microsoft.com/face/v1.0/detect';

	$headers = array(
	    // Request headers
	    'Content-Type: application/json',

	    // NOTE: Replace the "Ocp-Apim-Subscription-Key" value with a valid subscription key.
	    'Ocp-Apim-Subscription-Key: eeee342e022e4bcf99e30fe84d00efa5',
	    //'Ocp-Apim-Subscription-Key' => ' fa9a97b26e3547b09aa26eff259fa0bb',
	);
	
	$s = curl_init(); 

	curl_setopt($s, CURLOPT_URL, $request);
	curl_setopt($s, CURLOPT_HEADER, TRUE);
	curl_setopt($s, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($s, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($s, CURLOPT_RETURNTRANSFER, true); 
	curl_setopt($s, CURLOPT_POSTFIELDS, '{"url": "https://www.kodyac.tech/uploads/' . $target_file1 .'"}');
	
	$result1 = curl_exec($s);

	$s = curl_init(); 

	curl_setopt($s, CURLOPT_URL, $request);
	curl_setopt($s, CURLOPT_HEADER, TRUE);
	curl_setopt($s, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($s, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($s, CURLOPT_RETURNTRANSFER, true); 
	curl_setopt($s, CURLOPT_POSTFIELDS, '{"url": "https://www.kodyac.tech/uploads/' . $target_file2 .'"}');
	
	$result2 = curl_exec($s);
	
	$resultArr = explode("\n", $result1);
	$faceId1 = "";
	for($i = 0; $i < count($resultArr); $i++) {
		if($resultArr[$i][0] == '[') {
			$faceId1 = json_decode($resultArr[$i]);	
			break;
		}
	}
	
	$resultArr = explode("\n", $result2);
	$faceId2 = "";
	for($i = 0; $i < count($resultArr); $i++) {
		if($resultArr[$i][0] == '[') {
			$faceId2 = json_decode($resultArr[$i]);	
			break;
		}
	}
	
	$request = 'https://westcentralus.api.cognitive.microsoft.com/face/v1.0/verify';

	$headers = array(
	    // Request headers
	    'Content-Type: application/json',

	    // NOTE: Replace the "Ocp-Apim-Subscription-Key" value with a valid subscription key.
	    'Ocp-Apim-Subscription-Key: eeee342e022e4bcf99e30fe84d00efa5',
	    //'Ocp-Apim-Subscription-Key' => ' fa9a97b26e3547b09aa26eff259fa0bb',
	);
	
	$s = curl_init(); 

	curl_setopt($s, CURLOPT_URL, $request);
	curl_setopt($s, CURLOPT_HEADER, TRUE);
	curl_setopt($s, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($s, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($s, CURLOPT_RETURNTRANSFER, true); 
	curl_setopt($s, CURLOPT_POSTFIELDS, '{"faceId1": "' . $faceId1[0]->faceId . '", "faceId2": "' . $faceId2[0]->faceId . '"}');
	
	$result = curl_exec($s);
	
	$resultArr = explode("\n", $result);
	$verification = "";
	for($i = 0; $i < count($resultArr); $i++) {
		if($resultArr[$i][0] == '{') {
			$verification = json_decode($resultArr[$i]);	
			break;
		}
	}
	
	$response["success"] = true;
	$response["verification"] = $verification;
	
	echo(json_encode($response));
?>
