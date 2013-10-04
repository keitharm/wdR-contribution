<?php

header('Content-Type: image/png');

// Username to fetch
define("USERNAME", "TheMaster");

// Critical value based on web templated being fetched from
define("REP_POS_OFFSET", 21);

$url = "http://webdevrefinery.com/forums/members/?sort_key=posts&sort_order=desc&max_results=60&st=";

/* THESE ARE COMMENTED ONLY BECAUSE I DIDN'T NEED THEM FOR MY USER. IF IT CAN'T FIND YOUR USERNAME.

   UNCOMMENT THE ABOVE TO LINES TO INCREASE THE SEARCH AREA. */

// Finding username and cutting string to start at first occurance of it
$CutToUserPos = strpos($page, USERNAME);
$CutToUser = substr($page, $CutToUserPos, 1500);

// Finding rep amount
$CutToRepPos = strpos($CutToUser, "<span class='number'>");
$CutToRep = substr($CutToUser, $CutToRepPos + REP_POS_OFFSET, 5);
$RepAmount = filter_var($CutToRep, FILTER_SANITIZE_NUMBER_INT);

// Finding URL to the user's avatar
$CutToAvatar = substr($page, $CutToUserPos - 400, 500);
$FindAvatarURLPos = strpos($CutToAvatar, "<img src='");
$FindAvatarURL = substr($CutToAvatar, $FindAvatarURLPos, 200);
$URL = explode("'", $FindAvatarURL);

// Saving URL to file
$AvatarString = file_get_contents($URL[1]);

// Finding days since the user first joined
$CutToJoinDatePos = strpos($CutToUser, "Joined:");
$CutToJoinDate = substr($CutToUser, $CutToJoinDatePos, 35);
$ShorterDatePos = strpos($CutToJoinDate, "</span> ");
$ShorterDate = substr($CutToJoinDate, $ShorterDatePos + 8, 20);
$date = explode("<", $ShorterDate);
$time = time();
$timeago = $time - strtotime($date[0]);
$daysago = floor($timeago / 60 / 60 / 24);

// Calculating the amount of reputation per day
$RepPerDay = round(($RepAmount/$daysago), 3);

// Creating the string to be written to the signature image
$string = USERNAME . " has " . $RepAmount . " rep points. That's " . $RepPerDay . " rep per day!";

$im = imagecreate(800, 110);
$background_color = imagecolorallocate($im, 248, 248, 248);
$text_color = imagecolorallocate($im, 0, 0, 255);
imagestring($im, 15, 110, 50, $string, $text_color);
imagepng($im , "signature.png");


// Overlaying the previously generated text only signature, with the user's avatar
$signature = imagecreatefrompng("signature.png");
$avatar = imagecreatefromstring($AvatarString);
$size = getimagesize($URL[1]);
imagecopy($signature, $avatar, (110-$size[0])/2, (110-$size[1])/2, 0, 0, $size[0], $size[1]); // Also calculates how to center the avatar

imagepng($signature);