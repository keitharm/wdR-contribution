<?php
require_once("functions.php");
header('Content-Type: image/png');

// Username to fetch
define("USERNAME", $_GET['user']);

$userData = getUserData(USERNAME);

$RepPerDay = round(($userData->reputation) / floor((time() - $userData->joindate) / 86400), 3);

// Creating the string to be written to the signature image
$string = USERNAME . " has " . $userData->reputation . " rep points. That's " . $RepPerDay . " rep per day!";

$im = imagecreatetruecolor(800, 110);
$background_color = imagecolorallocate($im, 248, 248, 248);
imagefill($im, 0, 0, $background_color);
$text_color = imagecolorallocate($im, 0, 0, 255);
imagestring($im, 15, 110, 50, $string, $text_color);

// Overlaying the previously generated text only signature, with the user's avatar
$avatar = imagecreatefromstring(file_get_contents($userData->avatar));
$size = getimagesize($userData->avatar);
imagecopy($im, $avatar, (110-$size[0])/2, (110-$size[1])/2, 0, 0, $size[0], $size[1]); // Also calculates how to center the avatar

imagepng($im);

?>
