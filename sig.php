<?php

header('Content-Type: image/png');
require_once("functions.php");

// Username to fetch
define("USERNAME", "TheMaster");

// Critical value based on web templated being fetched from

$userData = getUserData(USERNAME);

$RepPerDay = (($userData->reputation) / (date() - $userData->joindate)) / 86400;

// Creating the string to be written to the signature image
$string = USERNAME . " has " . $RepAmount . " rep points. That's " . $RepPerDay . " rep per day!";

$im = imagecreate(800, 110);
$background_color = imagecolorallocate($im, 248, 248, 248);
$text_color = imagecolorallocate($im, 0, 0, 255);
imagestring($im, 15, 110, 50, $string, $text_color);


// Overlaying the previously generated text only signature, with the user's avatar
$avatar = imagecreatefromstring($userData->avatar);
$size = getimagesize($userData->avatar);
imagecopy($im, $avatar, (110-$size[0])/2, (110-$size[1])/2, 0, 0, $size[0], $size[1]); // Also calculates how to center the avatar

imagepng($im);

?>