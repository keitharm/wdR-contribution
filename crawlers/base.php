<?php
/*
	This script should only be run ONCE at the start of contribution calculations.
	This is so that we can calculate how many posts the user has made
	since we have started our leaderboards system
*/
set_time_limit(0);
require_once("../functions.php");

# Base URL
define("URL", "http://webdevrefinery.com/forums/members/?sort_key=joined&sort_order=asc&max_results=20&st=");

$tmp_data = file_get_contents(URL);
$numofpages = extractData($tmp_data, "Page 1 of ", " <!--<img", 1);

# Number of pages to extract
define("PAGES", $numofpages);

echo "--------------Settings--------------\n";
echo "Pages to fetch: \t\t" . PAGES . "\n";
echo "Total expected results: \t" . PAGES*20 . "\n";

// Fetch and extract data
echo "\nFetching data from wdR...\n";
for ($a = 0; $a < PAGES; $a++) {
	$data = file_get_contents(URL . $a*20);

	// Extract user data from members list
	$reps  = extractData($data, "<span class='number'>", "</span>");
	$posts = extractData($data, "</span><span class='left'>", "</span>");
	$userid = extractData($data, "<li id='member_id_", "' class='ipsP");

	for ($b = 0; $b < 20; $b++) {
		// Add user base values to DB if they have at least 1 post and aren't in the base table
		if ($posts[$b] > 0 && !userExistsBase($userid[$b])) {
			addUserBase($userid[$b], $posts[$b], $reps[$b]);
		} else {
			++$old;
		}
	}
    echo "Fetched and saved " . ((($a+1)*20)-$old) . " / " . PAGES*20 . " acceptable member profiles\t(" . round((($a+1)/PAGES)*100,2 ) . "% complete)\n";
}

function addUserBase($userid, $posts, $reputation) {
    $db = database();
    $statement = $db->prepare("INSERT INTO `base` (`userid`, `posts`, `reputation`) VALUES (?, ?, ?)");
    $statement->execute(array($userid, $posts, $reputation));
}
?>
