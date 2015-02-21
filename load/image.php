<?php
/*********************************************************************************************/
// This file is a property of Tesia Dynamics (TM) and is a part of Tesia Manager CMS. 
/*********************************************************************************************/
// usage: "../classes/img.php?w=WIDTH&h=HEIGHT&nocrop=0&full=0&water=1&gray=0&f=images/FOLDER/FILE" //

// get settings //
$gray = $_GET['gray'];  
$water = $_GET['water']; 
$nocrop = $_GET['nocrop']; 
$full = $_GET['full'];
$gray = $_GET['gray']; 
$file = $_GET['file']; 
$category = $_GET['category']; 
$folder = $_GET['folder']; 
$images = $_GET['images']; 
$imagescode = $_GET['imagescode']; 

if($folder) $foldertmp = $folder."/";
if($category!=0 && $category) $foldertmp .= $category."/";
if($imagescode) $foldertmp .= $imagescode."/";

if($images) {
	$source = "../files/images/".$foldertmp.$file;
} else {
	$foldertmp = '../files/'; 
	$source = $foldertmp.$file; 
}
$watermarkwidth =  150;  
$watermarkheight =  100;
define(DESIRED_WIDTH, $_GET['w']); 
define(DESIRED_HEIGHT, $_GET['h']);

list($source_width, $source_height, $source_type) = getimagesize($source);
switch ($source_type)
{
    case IMAGETYPE_GIF:   $source_gdim = imagecreatefromgif($source);  $makepng=1; break;
    case IMAGETYPE_JPEG:  $source_gdim = imagecreatefromjpeg($source); break;
    case IMAGETYPE_PNG:   $source_gdim = imagecreatefrompng($source); $makepng=1;  break;
}

if($full) { 
	if($source_width > DESIRED_WIDTH) { $dwidth = DESIRED_WIDTH; $dheight = (int)$source_height * (DESIRED_WIDTH / $source_width); } 
	else { $dwidth = $source_width; $dheight = $source_height; }	
	$desired_gdim = imagecreatetruecolor($dwidth, $dheight);
	imagecopyresampled($desired_gdim, $source_gdim, 0, 0, 0, 0, $dwidth, $dheight, $source_width, $source_height);
	if($makepng) imagealphablending($desired_gdim,true); 
} else {
	$source_ar = $source_width / $source_height;  $desired_ar = DESIRED_WIDTH / DESIRED_HEIGHT;
	if($source_ar > $desired_ar) { $temp_height = DESIRED_HEIGHT; $temp_width = (int)(DESIRED_HEIGHT * $source_ar); }
	elseif($source_ar == $desired_ar) {  $temp_height = DESIRED_HEIGHT; $temp_width = DESIRED_WIDTH; }
	else { $temp_width = DESIRED_WIDTH; $temp_height = (int)(DESIRED_WIDTH / $source_ar); }

	$temp_gdim = imagecreatetruecolor($temp_width, $temp_height);
	
	imagecopyresampled($temp_gdim, $source_gdim, 0, 0, 0, 0, $temp_width, $temp_height, $source_width, $source_height);
	if($makepng) imagealphablending($temp_gdim,true); 
	$x0 = ($temp_width - DESIRED_WIDTH) / 2;   $y0 = ($temp_height - DESIRED_HEIGHT) / 2;

	if($nocrop) { $desired_gdim = $temp_gdim;  } 
	else { $desired_gdim = @imagecreatetruecolor(DESIRED_WIDTH, DESIRED_HEIGHT);
	if($makepng) imagealphablending($desired_gdim,true); 
/*	if($makepng) {			
		imagealphablending($desired_gdim,true);
		imagealphablending($desired_gdim, false);
		imagesavealpha($desired_gdim, true);
		$transparent = imagecolorallocatealpha($desired_gdim, 255, 255, 255, 127);
 		imagefilledrectangle($desired_gdim, 0, 0, DESIRED_WIDTH, DESIRED_HEIGHT, $transparent);
 		$color = imagecolorallocate($desired_gdim, 255, 255, 255);
		imagefill($desired_gdim, 0, 0, $color);
	}*/
	
	imagecopy($desired_gdim, $temp_gdim, 0, 0, $x0, $y0, DESIRED_WIDTH, DESIRED_HEIGHT); }
	if($makepng) imagealphablending($desired_gdim,true);
}

// additional //
	if($gray)  { 
		imagefilter($desired_gdim, IMG_FILTER_GRAYSCALE); 
	}
	/*if($water) { 
		$water = imagecreatefrompng('../img/theme/water.png');
		$imagewidth = imagesx($desired_gdim); $imageheight = imagesy($desired_gdim); 
		$startwidth = $imagewidth - $watermarkwidth - 5;   $startheight = $imageheight - $watermarkheight;		
		imagecopy($desired_gdim, $water, $startwidth, $startheight, 0, 0, $watermarkwidth, $watermarkheight);
		imagealphablending($desired_gdim,true);
	}*/

if(!$makepng) { 
	header('Content-type: image/jpeg');
	imagejpeg($desired_gdim);
} else { 
	imagealphablending($desired_gdim, false);
	imagesavealpha($desired_gdim, true);
	header('Content-type: image/png');
	imagepng($desired_gdim);
}

imagedestroy($desired_gdim);

?>
