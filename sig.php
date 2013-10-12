<?php
require_once("functions.php");
header('Content-Type: image/png');

// Username to fetch
define("USERNAME", $_GET['user']);

$userData = getUserData(USERNAME);
$state = getUserOnlineState(USERNAME);

$rep = $userData->reputation;
$RepPerDay = round(($rep) / floor((time() - $userData->joindate) / 86400), 3);
$joined = date('l jS \of F\, Y', $userData->joindate);
$posts = $userData->posts;

// Creating the string to be written to the signature image
$string = USERNAME . " has " . $userData->reputation . " rep points. That's " . $RepPerDay . " rep per day!";

$im = imagecreatetruecolor(800, 110);
$bg = imagecreatefrompng("images/sig_background.png");
imagecopyresampled($im, $bg, 0, 0, 0, 0, 800, 110, 800, 110);

$text_color = imagecolorallocate($im, 0, 0, 255);
imagettftext($im, 13, 0, 110, 40, $text_color, "fonts/helvetica.ttf", $string);
imagettftext($im, 9, 0, 110, 65, $text_color, "fonts/helvetica.ttf", "Joined: " . $joined);
imagettftext($im, 9, 0, 110, 80, $text_color, "fonts/helvetica.ttf", "Posts: " . $posts);
imagettftext($im, 9, 0, 110, 95, $text_color, "fonts/helvetica.ttf", "Reputation: " . $rep);

// Overlaying the previously generated text only signature, with the user's avatar
$avatar = imagecreatefromstring(file_get_contents($userData->avatar));
$size = getimagesize($userData->avatar);
imagecopy($im, $avatar, (110-$size[0])/2, (110-$size[1])/2, 0, 0, $size[0], $size[1]); // Also calculates how to center the avatar


if ($state['Status']=="Online") {
    $stateImage = imagecreatefrompng("images/online.png");
    imagecopy($im, $stateImage, 660, 35, 0, 0, 120, 40);
} else {
    $stateImage = imagecreatefrompng("images/offline.png");
    imagecopy($im, $stateImage, 660, 35, 0, 0, 120, 40);
}

imagepng($im);

?>
