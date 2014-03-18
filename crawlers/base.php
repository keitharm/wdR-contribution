<?php
/*
	This script should only be run ONCE at the start of contribution calculations.
	This is so that we can calculate how many posts the user has made
	since we have started our leaderboards system
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

$old = 0;
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

	// Extract user data from members list
	$reps  = extractData($data, "<span class='number'>", "</span>");
	$posts = extractData($data, "</span><span class='left'>", " posts</span>");
	$userid = extractData($data, "<li id='member_id_", "' class='ipsP");
	// Break out of loop next time it goes around
	if (in_array(4, $posts)) {
		$loop = false;
	}

	for ($b = 0; $b < 20; $b++) {
		// Add user base values to DB if they have at least 5 posts and aren't in the base table
		if ($posts[$b] >= 5 && !userExistsBase($userid[$b])) {
			addUserBase($userid[$b], $posts[$b], $reps[$b]);
		} else {
			++$old;
		}
	}
    echo "Fetched and saved " . ((($a+1)*20)-$old) . " / " . round((PAGES*20)*POSTS_RATIO) . " acceptable member profiles\t(" . round(($a+1)/round(((PAGES*20)*POSTS_RATIO/20))*100,2) . "% complete |\tETA " . $eta . " - " . $eta_str . ")\n";
    $eta_new = timer();
    $eta_times[] = $eta_new - $eta_old;
    $eta_raw = round(((array_avg($eta_times))*(round(((PAGES*20)*POSTS_RATIO)/20)-($a+1))));
    $eta_old = $eta_new;
}
echo "A total of " . ((($a)*20)-$old) . " new members were added in " . round($eta_new-$eta_start) . " seconds.\n";

function addUserBase($userid, $posts, $reputation) {
    $db = database();
    $statement = $db->prepare("INSERT INTO `base` (`userid`, `posts`, `reputation`) VALUES (?, ?, ?)");
    $statement->execute(array($userid, $posts, $reputation));
}
?>
