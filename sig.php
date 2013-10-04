<?php

	//variable inputs
	define(USERNAME, "TheMaster");
	
	//critical values that could change if the website is updated
	define("REP_POS_OFFSET", 21);

	$url = "http://webdevrefinery.com/forums/members/?sort_key=posts&sort_order=desc&max_results=60&st=";

	$page = file_get_contents($url . "0");
	//$page = $page . file_get_contents($url . "60");
	//$page = $page . file_get_contents($url . "120");

        /* THESE ARE COMMENTED ONLY BECAUSE I DIDN'T NEED THEM FOR MY 
USER. IF IT CAN'T FIND YOUR USERNAME.
           UNCOMMENT THE ABOVE TO LINES TO INCREASE THE SEARCH AREA. */

	//finding username and cutting string to start at first occurance of it
	$CutToUserPos = strpos($page, USERNAME);
	$CutToUser = substr($page, $CutToUserPos, 1500);

	//finding rep amount
	$CutToRepPos = strpos($CutToUser, "<span class='number'>");
	$CutToRep = substr($CutToUser, $CutToRepPos + REP_POS_OFFSET, 5);
	$RepAmount = filter_var($CutToRep, FILTER_SANITIZE_NUMBER_INT);

	//find days since joined
	$CutToJoinDatePos = strpos($CutToUser, "Joined:");
	$CutToJoinDate = substr($CutToUser, $CutToJoinDatePos, 35);
	$ShorterDatePos = strpos($CutToJoinDate, "</span> ");
	$ShorterDate = substr($CutToJoinDate, $ShorterDatePos + 8, 20);

	$date = explode("<", $ShorterDate);

	$time = time();
	$timeago = $time - strtotime($date[0]);

	$daysago = floor($timeago / 60 / 60 / 24);

	$RepPerDay = round(($RepAmount/$daysago), 3);

	$string = USERNAME . " has " . $RepAmount . " rep points, over 
the course of " . $daysago . " days. That's " . $RepPerDay . " rep per 
day!";

	$im = imagecreate(800, 25);
	$background_color = imagecolorallocate($im, 248, 248, 248);
	$text_color = imagecolorallocate($im, 0, 0, 255);
	imagestring($im, 10, 10, 10, $string, $text_color);
	imagepng($im);
?>