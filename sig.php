<?php
require_once("functions.php");

// Username to fetch
define("USERNAME", fixUsername($_GET['user']));

if (isset($_GET['theme'])) {
    define("SIG_THEME", $_GET['theme']);
} else {
    die("No theme selected.");
}


// Stop script if username doesn't exist in DB
if (USERNAME == null) {
	die("Invalid User");
}

$userData = getUserData(USERNAME);
$state = getUserOnlineState(USERNAME);

$rep = $userData->reputation;
$RepPerDay = round(($rep) / floor((time() - $userData->joindate) / 86400), 3);
$joined = date('l jS \of F\, Y', $userData->joindate);
$posts = $userData->posts;
$rank = getUserRank(USERNAME);

// Creating the string to be written to the signature image
$string = USERNAME . " has " . $userData->reputation . " rep points. That's " . $RepPerDay . " rep per day!";

$im = imagecreatetruecolor(800, 110);

if (SIG_THEME=="light") {
    $bg = imagecreatefrompng("images/sig_back_light.png");
    $text_color = imagecolorallocate($im, 0, 0, 255);
}
if (SIG_THEME=="dark") {
    $bg = imagecreatefrompng("images/sig_back_dark.png");
    $text_color = imagecolorallocate($im, 255, 255, 255);
}

imagecopyresampled($im, $bg, 0, 0, 0, 0, 800, 110, 800, 110);
imagettftext($im, 13, 0, 110, 30, $text_color, "fonts/helvetica.ttf", $string);
imagettftext($im, 13, 0, 110, 52, $text_color, "fonts/helvetica.ttf", "Current Rank: " . $rank);
imagettftext($im, 9, 0, 110, 75, $text_color, "fonts/helvetica.ttf", "Joined: " . $joined);
imagettftext($im, 9, 0, 110, 90, $text_color, "fonts/helvetica.ttf", "Posts: " . $posts);

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

header('Content-Type: image/png');
imagepng($im);
?>