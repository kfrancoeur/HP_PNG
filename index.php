<?php
if (count($_REQUEST) != 0){ //At least some parameters were added.

	//Initialize all variables based on $_GET.
	$img_c = (ISSET($_GET['c']) && (strlen($_GET['c']) == 6) && ctype_xdigit($_GET['c']) 
		? $_GET['c'] : "00dd00"); //Color of HP bar; default Green.
	$img_c2 = (ISSET($_GET['c2']) && (strlen($_GET['c2']) == 6) && ctype_xdigit($_GET['c2']) 
		? $_GET['c2'] : hexcolorreduc($img_c) ); //Color of HP bar; default darker $img_c.
	$img_bg = (ISSET($_GET['bg']) && (strlen($_GET['bg']) == 6) && ctype_xdigit($_GET['bg'])
		? $_GET['bg'] : "777777"); //Color of background; default grey
	$img_bg2 = (ISSET($_GET['bg2']) && (strlen($_GET['bg2']) == 6) && ctype_xdigit($_GET['bg2'])
		? $_GET['bg2'] : hexcolorreduc($img_bg) ); //Color of background; default darker bg2
	$img_ol = (ISSET($_GET['ol']) && (strlen($_GET['ol']) == 6) && ctype_xdigit($_GET['ol'])
		? str_split($_GET['ol'],2) : array("00","00","00") ); //Color of outlines; default black
		
	$img_w = (ISSET($_GET['w']) && is_numeric($_GET['w']) 
		? $_GET['w'] : 200); //Width of HP bar; default 200
	$img_h = (ISSET($_GET['h']) && is_numeric($_GET['h']) 
		? $_GET['h'] : 40); //Height of HP bar; default 40
		
	$hp_cur = (ISSET($_GET['chp']) && is_numeric($_GET['chp']) 
		? $_GET['chp'] : 100); //Current HP; default 100
	$hp_max = (ISSET($_GET['mhp']) && is_numeric($_GET['mhp']) 
		? $_GET['mhp'] : 100); //Max HP; default 100
	

	$img_hp = ($img_w / $hp_max) * $hp_cur;

	//Create both halves of the bar.
	$my_img = imagecreatetruecolor($img_w,$img_h);
	image_gradientrect($my_img, 0, 0, $img_w, $img_h, $img_c, $img_c2);
	image_gradientrect($my_img, $img_hp, 0, $img_w, $img_h, $img_bg, $img_bg2);

	//Border between "current" and "max" portions of HP bar 
	imagesetthickness ( $my_img, 2 );
	$line_color = imagecolorallocate( $my_img, hexdec($img_ol[0]), hexdec($img_ol[1]), hexdec($img_ol[2]) );
	imageline( $my_img, $img_hp, 1, $img_hp, $img_h, $line_color );

	//Black borders ERRYWHERE
	imageline( $my_img, 0, 1, $img_w, 1, $line_color );
	imageline( $my_img, 0, $img_h-1, $img_w, $img_h-1, $line_color );
	imageline( $my_img, 1, 0, 1, $img_h, $line_color );
	imageline( $my_img, $img_w-1, 0, $img_w-1, $img_h, $line_color );

	//Setting up for text
	if (ISSET($_GET['text'])){
		$font_color = imagecolorallocate($my_img, 255, 255, 255);
		$stroke_color = imagecolorallocate($my_img, 0, 0, 0 );
		imagettfstroketext($my_img, 14, 0, 10, 27, $font_color, $stroke_color, "fonts/arial.ttf", $hp_cur . " / " . $hp_max, 2);
	}

	imagepng( $my_img );
	header( "Content-type: image/png" );
	imagedestroy( $my_img );
	
}else{
$textblock = '<h1>HP_PNG</h1>
<p>HP_PNG is a $_GET-using, dynamic image creator designed for use with resource bars for PbP games.<br>
Available variables are as follows:</p>
<h2></span>"c":</h2>
<ul>
<li>The color of the HP bar, in RGB hex.  Defaults to <em>"00dd00"</em> (green)</li>
</ul>
<h2>"c2":</h2>
<ul>
<li>The secondary bottom color of the HP bar, in RGB hex. Darker version of "c" by default.</li>
</ul>
<h2>"bg":</h2>
<ul>
<li>The color of the background / empty HP field, in RGB hex.  Defaults to <em>"777777"</em> (grey)</li>
</ul>
<h2>"bg2":</h2>
<ul>
<li>The secondary bottom color of the background, in RGB hex. Darker version of "bg" by default.</li>
</ul>
<h2>"ol":</h2>
<ul>
<li>The color of the outline, in RGB hex. Defaults to <em>"000000"</em>.</li>
</ul>
<h2>"w":</h2>
<ul>
<li>The width of the image in pixels.  Defaults to <em>200 pixels</em>.</li>
</ul>
<h2>"h":</h2>
<ul>
<li>The height of the image in pixels.  Defaults to <em>40 pixels</em>.</li>
</ul>
<h2>"mhp":</h2>
<ul>
<li>The user"s maximum HP.  Defaults to <em>100</em>.</li>
</ul>
<h2>"chp":</h2>
<ul>
<li>The users current HP.  Defaults to <em>100</em>.</li>
</ul>
<h2>"text":</h2>
<ul>
<li>Display text if exists.  Defaults to not display.</li>
</ul>
<p>Like all $_GET requests, variables should be appended to the end of the URL.  For example:</p>
<ul>
<li>localhost:8080/dev/index.php?c=dd0000&amp;mhp=543&amp;chp=500&amp;text</li>
<li>This URL will create a red bar with 500 out of 543 HP, with the HP text overlaid on top of the image.</li>
</ul>';
	
echo $textblock;
}

function image_gradientrect($img,$x,$y,$x1,$y1,$start,$end) {
	//Function by Harshal Mahajan on Stack Overflow
	//http://stackoverflow.com/questions/24822223/how-to-draw-a-gradient-rectangle-in-php
	//Creates a gradient between two input colors.
	
	if($x > $x1 || $y > $y1) {
	  return false;
	}
	$s = array(
	  hexdec(substr($start,0,2)),
	  hexdec(substr($start,2,2)),
	  hexdec(substr($start,4,2))
	);
	$e = array(
	  hexdec(substr($end,0,2)),
	  hexdec(substr($end,2,2)),
	  hexdec(substr($end,4,2))
	);
	$steps = $y1 - $y;
	for($i = 0; $i < $steps; $i++) {
	  $r = $s[0] - ((($s[0]-$e[0])/$steps)*$i);
	  $g = $s[1] - ((($s[1]-$e[1])/$steps)*$i);
	  $b = $s[2] - ((($s[2]-$e[2])/$steps)*$i);
	  $color = imagecolorallocate($img,$r,$g,$b);
	  imagefilledrectangle($img,$x,$y+$i,$x1,$y+$i+1,$color);
	}
	return true;
}

function imagettfstroketext(&$image, $size, $angle, $x, $y, &$textcolor, &$strokecolor, $fontfile, $text, $px) {
	//Function by John Ciacia on JohnCiacia.com
	//http://www.johnciacia.com/2010/01/04/using-php-and-gd-to-add-border-to-text/
	//Creates text with an outline.
 
    for($c1 = ($x-abs($px)); $c1 <= ($x+abs($px)); $c1++)
        for($c2 = ($y-abs($px)); $c2 <= ($y+abs($px)); $c2++)
            $bg = imagettftext($image, $size, $angle, $c1, $c2, $strokecolor, $fontfile, $text);
 
	return imagettftext($image, $size, $angle, $x, $y, $textcolor, $fontfile, $text);
}

function hexcolorreduc($color){
	//Darken passed in hex color
	$color = str_split($color, 2);
	
	foreach ($color as &$value){
		$value = "0x" . $value; //Manually converting to hex.
		$value = $value - "0x33";
		$value = ($value <= 0) ? "00" : dechex($value);
	}
	$color = implode($color);

	return $color;
}

?>