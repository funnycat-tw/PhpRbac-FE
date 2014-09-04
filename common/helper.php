<?php
//         File: helper.php
//  Description: some helper function for common usage
//
//Revision:2014090401
//

class CommonHelper {

	function is_valid($str, $pattern, &$assign_to) {
		$result = FALSE;

		if( preg_match($pattern, $str) == 1 ) {
			$assign_to = $str;
			$result = TRUE;	
		}

		return $result;
	} // is_valid
}
?>
