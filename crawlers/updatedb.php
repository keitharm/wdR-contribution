<?php
/*
	This script should run once daily to fetch new values, update totals and ranks, etc.
*/ 
set_time_limit(0);
require_once("../functions.php");

# Base URL
define("URL", "http://webdevrefinery.com/forums/members/?sort_key=posts&sort_order=desc&max_results=20&st=");

$tmp_data = file_get_contents(URL);
$numofpages = extractData($tmp_data, "Page 1 of ", " <!--<img", 1);

# Number of pages to extract
define("PAGES", $numofpages);

echo "--------------Settings--------------\n";
echo "Total Pages to fetch: \t\t\t" . PAGES . "\n";
echo "Total results: \t\t\t\t" . PAGES*20 . "\n";
echo "Estimated actual pages to fetch: \t" . round(((PAGES*20)*.0722)/20) . "\n";
echo "Estimated actual results: \t\t" . round((PAGES*20)*.0722) . "\n";

// Constant date for when we add new entries to history list
$date = time();

// What cycle are we on? (Cycle count goes up every daily data fetch from wdR)
$cycle = getLastCycle();

// Fetch and extract data
echo "\nFetching data from wdR...\n";

// Loop check is true until it reaches member with 4 posts
$loop = true;
for ($a = 0; $a < PAGES; $a++) {
	if (!$loop) {
		break;
	}
	$data = file_get_contents(URL . $a*20);

	// Initialize/Emptry status array
	$status = array();

	// Extract values from wdR member list
	$reps  = extractData($data, "<span class='number'>", "</span>");
	$names = extractData($data, "View Profile'>", "</a>");
	$posts = extractData($data, "</span><span class='left'>", " posts</span>");
	$avatars = extractData($data, "left'><img src='", "' alt=");
	$userid = extractData($data, "<li id='member_id_", "' class='ipsP");

	// Break out of loop next time it goes around
	if (in_array(4, $posts)) {
		$loop = false;
	}

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
		if (userExistsBase($userid[$c])) {
			addEntry($userid[$c], $names[$c], $date, ($cycle+1), $avatars[$c], $posts[$c], $reps[$c], $status[$c]);
			calculateTotals($userid[$c]);
		}
	}
	
	updateHistoryRanks();
    updateRanks();

    echo "Fetched, saved, and updated " . ((($a+1)*20)-$old) . " / " . round((PAGES*20)*.0722) . " acceptable member profiles\t(" . round((($a+1)/round(((PAGES*20)*.0722)/20))*100,2 ) . "% complete)\n";
}
?>
