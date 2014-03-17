<?php
/*
	This script should run once daily to fetch new values, update totals and ranks, etc.
*/
chdir(dirname(__FILE__));
set_time_limit(0);
require_once("../functions.php");

# Number of pages to extract
define("PAGES", numofpages());

echo "--------------Settings--------------\n";
echo "Total Pages to fetch: \t\t\t" . PAGES . "\n";
echo "Total results: \t\t\t\t" . PAGES*20 . "\n";
echo "Estimated actual pages to fetch: \t" . round(((PAGES*20)*POSTS_RATIO)/20) . "\n";
echo "Estimated actual results: \t\t" . round((PAGES*20)*POSTS_RATIO) . "\n";

// What cycle are we on? (Cycle count goes up every daily data fetch from wdR)
$cycle = getLastCycle();

// Fetch and extract data
echo "\nFetching data from wdR...\n";

// Start ETA timer
$eta_start = timer();

// ETA calculations
$eta_times = array();
$eta_old = $eta_start;
$eta_raw = 0;
$eta = "--:--";
$eta_str = "--:--";

// Loop check is true until it reaches member with 4 posts
$loop = true;
for ($a = 0; $a < PAGES; $a++) {
	if (!$loop) {
		break;
	}
	// ETA updater
	if ($eta_raw != 0) {
		$mins = floor($eta_raw/60);
		$secs = ($eta_raw - ($mins*60));
		$eta = sprintf("%02s", $mins) . ":" . sprintf("%02s", $secs);
		$eta_str = date("H:i:s", time()+$eta_raw);
	}

	$data = file_get_contents(URL . $a*20);

	// Initialize/Empty status array
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
			addEntry($userid[$c], $names[$c], time(), ($cycle+1), $avatars[$c], $posts[$c], $reps[$c], $status[$c]);
			calculateTotals($userid[$c]);
		}
	}

    echo "Fetched, saved, and updated " . ((($a+1)*20)-$old) . " / " . round((PAGES*20)*POSTS_RATIO) . " acceptable member profiles\t(" . round(($a+1)/round(((PAGES*20)*POSTS_RATIO/20))*100,2) . "% complete |\tETA " . $eta . " - " . $eta_str . ")\n";
    $eta_new = timer();
    $eta_times[] = $eta_new - $eta_old;
    $eta_raw = round(((array_avg($eta_times))*(round(((PAGES*20)*POSTS_RATIO)/20)-($a+1))));
    $eta_old = $eta_new;
}

echo "Updating History Rankings...\n";
updateHistoryRanks();

echo "Updating Current Rankings...\n";
updateRanks();

echo "Updating Last 30 Day Stats Cache...\n"
updateCache();

echo "A total of " . ((($a)*20)-$old) . " members stats were updated in " . round($eta_new-$eta_start) . " seconds.\n";
?>
