<?php
/*
	This script should run once daily to fetch new values, update totals and ranks, etc.
*/ 
set_time_limit(0);
require_once("../functions.php");

# Base URL
define("URL", "http://webdevrefinery.com/forums/members/?sort_key=posts&sort_order=desc&max_results=20&st=");
# Number of pages to extract
define("PAGES", 10);

echo "--------------Settings--------------\n";
echo "Pages to fetch: \t\t" . PAGES . "\n";
echo "Total expected results: \t" . PAGES*20 . "\n";

// Constant date for when we add new entries to history list
$date = time();

// What cycle are we on? (Cycle count goes up every daily data fetch from wdR)
$cycle = getLastCycle();

// Fetch and extract data
echo "\nFetching data from wdR...\n";
for ($a = 0; $a < PAGES; $a++) {
	$data = file_get_contents(URL . $a*20);

	// Initialize/Emptry status array
	$status = array();

	// Extract values from wdR member list
	$reps  = extractData($data, "<span class='number'>", "</span>");
	$names = extractData($data, "View Profile'>", "</a>");
	$posts = extractData($data, "</span><span class='left'>", " posts</span>");
	$avatars = extractData($data, "left'><img src='", "' alt=");
	$userid = extractData($data, "<li id='member_id_", "' class='ipsP");

	// Find out if user has logged in within last 24 hours.
	foreach ($userid as $id) {
		$userprofile = file_get_contents("http://webdevrefinery.com/forums/user/" . $id . "-");
		$lastactive = extractData($userprofile, ">Last Active ", "</span>");
		$lastactive = strtotime($lastactive);
		if ((time()-$lastactive) <= 86400) {
			// Has logged in within 24 hours
			$status[] = 1;
		} else {
			// Nope
			$status[] = 0;
		}
	}

	// Fix for Avatars
	foreach ($avatars as &$image) {
		if (strpos($image, "gravatar.com") !== false) {
			$image = substr($image, 0, 69);
			if (file_get_contents($image) == file_get_contents("../images/gravatar_default.jpg")) {
				$image = "images/wdr_default.png";
			}
		}
	}

	// Add new stats within this 24 hour period to history and
	// calculate new totals by adding up user's previous 24h history periods
	for ($c = 0; $c < 20; $c++) {
		addEntry($userid[$c], $names[$c], $date, ($cycle+1), $avatars[$c], $posts[$c], $reps[$c], $status[$c]);
		calculateTotals($userid[$c]);
	}
	
	updateHistoryRanks();
    updateRanks();

    echo "Fetched, saved, and updated " . ($a+1)*20 . " / " . PAGES*20 . " member profiles\t(" . round((($a+1)/PAGES)*100,2 ) . "%)\n";
}
?>
