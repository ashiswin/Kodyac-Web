<?php
    
    function random_str($length = 6, $keyspace = '23456789abcdefghijkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ') {
        // Random string generator of default length 6
        // $length is an int and decides how long the output string is
        // Default keyspace is alphanumeric
		$str = '';
		$max = mb_strlen($keyspace, '8bit') - 1; // $max limits random_int to within the keyspace
		if ($max < 1) {
			throw new Exception('$keyspace must be at least two characters long');
		}
		for ($i = 0; $i < $length; ++$i) {
			$str .= $keyspace[random_int(0, $max)];
		}
		return $str;
	}

?>