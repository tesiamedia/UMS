<?php
/***********************************************************************************************/
/* TESIA CMS 5 | TESIA DYNAMICS [tm] | http://www.tesia.lt | All rights reserved               */
/***********************************************************************************************/
/* Global functions for repetitive coding 													   */
/* 2014-12-12 changes:					                          							   */
/* - added get_part() function. 													           */
/* - added mass_unserialize() function. 											           */
/***********************************************************************************************/

// sanitize string //


// strip part of string by explosion method //
function get_part($string,$delimiter,$element) {
	$tmp = explode($delimiter, $string);
	if(isset($tmp[$element])) {
		return trim($tmp[$element]);
	} else {
		return null;
	}
}

// mass array elements unserialization //
function mass_unserialize($array) {
	if(is_array($array)) {
		foreach($array as $key=>$val) {
			$tmp[$key] = @unserialize($val);
			if ($tmp[$key] === false) {
				$tmp[$key] = $val;
			}
		}
	}
	return $tmp;
}

// check if a valid email address //
function is_email($email){ 
	return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// emoticons //
function emoticons($text) { 
	$a = "<img src='img/smilies/"; $b = "' border='0' />";
	$codes = array(":-)", ":-D", ";-)", "8-)", ":-(", ":-P", "(heart)", "(green)", "(angry)", "(dum)", "(wink)", "X-|", "(cry)", "!!!", ">_>", "(eyes)", ":-|");
	$images = array($a."smile.gif".$b, $a."biggrin.gif".$b, $a."wink.gif".$b, $a."cool.gif".$b, $a."sad.gif".$b, $a."bleh.gif".$b, $a."beatingheart.gif".$b, $a."barf.gif".$b, $a."mad.gif".$b, $a."black_eye.gif".$b, $a."blink.gif".$b, $a."closedeyes.gif".$b, $a."cry2.gif".$b, $a."excl.gif".$b, $a."happy.gif".$b, $a."rolleyes.gif".$b, $a."wacko.gif".$b);
	return str_replace($codes, $images, $text);
}

// string check for numerical type //
function number($number) { 
	if(is_numeric($number)) 
		return $number; 
	else 
		return 0; 
}

// meta redirect //
function redirect($url, $time=0) { 
	echo "<meta http-equiv='refresh' content='".$time."; url=".$url."'>"; 
}

// defined value printer //
function langer($c) { 
	if(defined($c)) 
		return constant($c); 
	else 
		return $c;  
}

// strip text //
function strip_text($text, $limit) {  
	return mb_substr(strip_tags(stripslashes($text)), 0, $limit, 'utf-8');  
}

// date offset //
function date_offset($dt,$year_offset='',$month_offset='',$day_offset='') {
	return ($dt=='0000-00-00') ? '' : date("Y-m-d", mktime(0,0,0,substr($dt,5,2)+$month_offset,substr($dt,8,2)+$day_offset,substr($dt,0,4)+$year_offset));
}

// <br> to /n converter //
function br2nl($txt) { 
	return preg_replace('#<br\s*?/?>#i', "\n", $txt); 
} 

// recursive folder delete //
function rrmdir($dir) {
	if(is_dir($dir)) { 
		$objects = scandir($dir); 
		foreach ($objects as $object) {  
			if ($object != "." && $object != "..") {
				if (filetype($dir."/".$object) == "dir") 
					rrmdir($dir."/".$object); else unlink($dir."/".$object);  
			} 
		}
		reset($objects); 
		rmdir($dir); 
	}
} 

// browser detection //
function browser() { 
	$u_agent = $_SERVER['HTTP_USER_AGENT'];   
    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) { return "MSIE"; }
    elseif(preg_match('/Firefox/i',$u_agent)) { return "Firefox"; } 
	elseif(preg_match('/Chrome/i',$u_agent)) { return "Chrome"; }
    elseif(preg_match('/Safari/i',$u_agent)) { return "Safari"; }  
	elseif(preg_match('/Opera/i',$u_agent)) {  return "Opera"; }
	else return "Unknown";
}

?>