<?php  
/***********************************************************************************************/
/* TESIA CMS 5 | TESIA DYNAMICS [tm] | http://www.tesia.lt | All rights reserved               */
/***********************************************************************************************/

session_start();  

usleep(300000);

function readdir_files_byformat($dir, $format) {
	foreach(glob($dir.'*.'.$format) as $file) {
		 $tmp[] = $file;
	}	
	return $tmp;
}

// variables //
$ttf = readdir_files_byformat('../files/fonts/', 'ttf'); 
$font = array_rand($ttf);
$font = $ttf[$font];
$charset = '2346789puktsarijnvcsehzvydm';
if($administration) { 
	$code_length = 5; $height = 50; $width = 140; $code = ''; 
} else {  
	$code_length = 4; $height = 50; $width = 110; $code = '';  
}

// string generator //
for($i=0; $i < $code_length; $i++) {    
	$code = $code . substr($charset, mt_rand(0, strlen($charset) - 1), 1);  
}
$image = imagecreate($width, $height);
$background_color = imagecolorallocate($image, 240, 240, 240);
$noise_color = imagecolorallocate($image, rand(100,155), rand(100,255), rand(100,255)); 

// add noise //
for($i=0; $i < ($width * $height) / 8; $i++) {  
	imageellipse($image, mt_rand(0,$width), mt_rand(0,$height), 1, 1, $noise_color); 
}

// render text // 
$text_color = imagecolorallocate($image, 100, 100, 100); 
imagettftext($image, rand(18,24), rand(-7,7), rand(1,35), rand(20,40), $text_color, $font, $code);

header('Content-Type: image/png'); 
imagepng($image); 

// set session //
$_SESSION['login_captcha'] = $code;

imagedestroy($image);
exit();

?>