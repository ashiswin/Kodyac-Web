<?php
	include "utils/database.php";
	include "connectors/ProfileConnector.php";
	
	function base64_to_jpeg($base64_string, $output_file) {
		// open the output file for writing
		$ifp = fopen( $output_file, 'wb' ); 

		// split the string on commas
		// $data[ 0 ] == "data:image/png;base64"
		// $data[ 1 ] == <actual base64 string>
		$data = explode( ',', $base64_string );

		// we could add validation here with ensuring count( $data ) > 1
		if(count($data) == 2) {
			fwrite( $ifp, base64_decode( $data[ 1 ] ) );
		}
		else {
			fwrite($ifp, base64_decode($base64_string));
		}

		// clean up the file resource
		fclose( $ifp ); 

		return $output_file; 
	}
	
	function prettySex($s) {
		if(strcmp($s, "F") == 0) {
			return "FEMALE";
		}
		else if(strcmp($s, "M") == 0) {
			return "MALE";
		}
	}
	
	$linkid = $_POST['linkId'];
	$name = $_POST['name'];
	$address = $_POST['address'];
	$nationality = $_POST['nationality'];
	$dob = $_POST['dob'];
	$nric = $_POST['nric'];
	$sex = $_POST['sex'];
	$race = $_POST['race'];
	$image = $_POST['image'];
	
	$ProfileConnector = new ProfileConnector($conn);
	
	$ProfileConnector->updateOtherInfo($linkid, $name, $address, $nric, $nationality, $dob, prettySex($sex), $race);
	base64_to_jpeg($image, "../uploads/link_" . $linkid);
	
	$response["success"] = true;
	echo(json_encode($response));
?>
