<?php
set_time_limit(0);
require_once("functions.php");

# Base URL
define("URL", "http://webdevrefinery.com/forums/members/?sort_key=joined&sort_order=asc&max_results=20&st=");
# Number of pages to extract
define("PAGES", 547);

echo "--------------Settings--------------\n";
echo "Pages to fetch: \t\t" . PAGES . "\n";
echo "Total expected results: \t" . PAGES*20 . "\n";

$scores = array();

// Constant date and cycle
$date = time();
$cycle = getLastCycle();

// Fetch and extract data
echo "\nFetching data from wdR...\n";
for ($a = 0; $a < PAGES; $a++) {
	$reps = array();
	$names = array();
	$posts = array();
	$avatars = array();
	$userid = array();

	$data = file_get_contents(URL . $a*20);

	$reps_tmp  = extractData($data, "<span class='number'>", "</span>");
	$names_tmp = extractData($data, "View Profile'>", "</a>");
	$posts_tmp = extractData($data, "</span><span class='left'>", "</span>");
	$avatars_tmp = extractData($data, "left'><img src='", "' alt=");
	$userid_tmp = extractData($data, "<li id='member_id_", "' class='ipsP");

	$reps = array_merge($reps, $reps_tmp);
	$names = array_merge($names, $names_tmp);
	$posts = array_merge($posts, $posts_tmp);
	$avatars = array_merge($avatars, $avatars_tmp);
	$userid = array_merge($userid, $userid_tmp);

	// Update join dates into UNIX timestamp
	foreach ($joins as &$join) {
		$join = strtotime($join);
	}

	// Calculate scores with reputation * (posts/minutes since registration)
	for ($b = 0; $b < 20; $b++) {
		 $scores[] = ($reps[$b]*($posts[$b]/((time()-$joins[$b])/86400)));
	}

	// Fix for Avatars
	foreach ($avatars as &$image) {
		if (strpos($image, "gravatar.com") !== false) {
			$image = substr($image, 0, 69);
			if (file_get_contents($image) == file_get_contents("images/gravatar_default.jpg")) {
				$image = "images/wdr_default.png";
			}
		}
	}

	for ($c = 0; $c < 20; $c++) {
		addEntry($userid[$c], $names[$c], $time, $cycle, $avatars[$c], (($posts[$c]*10) + ($reps[$c]*25) + ($loggedon*5)), $posts[$c], $reps[$c], $loggedon);
	}
    echo "Fetched and saved " . ($a+1)*20 . " / " . PAGES*20 . " member profiles\t(" . round((($a+1)/PAGES)*100,2 ) . "%)\n";
}
?>
