<?php
set_time_limit(0);
require_once("../functions.php");

# Base URL
define("URL", "http://webdevrefinery.com/forums/members/?sort_key=posts&sort_order=desc&max_results=20&st=");
# Number of pages to extract
define("PAGES", 2);

echo "--------------Settings--------------\n";
echo "Pages to fetch: \t\t" . PAGES . "\n";
echo "Total expected results: \t" . PAGES*20 . "\n";

// Constant date and cycle
$date = time();
$cycle = getLastCycle();

// Fetch and extract data
echo "\nFetching data from wdR...\n";
for ($a = 0; $a < PAGES; $a++) {
	$data = file_get_contents(URL . $a*20);

	$reps = array();
	$names = array();
	$posts = array();
	$avatars = array();
	$userid = array();
	$status = array();
	$status_tmp = array();

	$reps_tmp  = extractData($data, "<span class='number'>", "</span>");
	$names_tmp = extractData($data, "View Profile'>", "</a>");
	$posts_tmp = extractData($data, "</span><span class='left'>", " posts</span>");
	$avatars_tmp = extractData($data, "left'><img src='", "' alt=");
	$userid_tmp = extractData($data, "<li id='member_id_", "' class='ipsP");

	// Find out if user has logged in within last 24 hours.
	foreach ($userid_tmp as $id) {
		$userprofile = file_get_contents("http://webdevrefinery.com/forums/user/" . $id . "-");
		$lastactive = extractData($userprofile, ">Last Active ", "</span>");
		$lastactive = strtotime($lastactive);
		if ((time()-$lastactive) <= 86400) {
			$status_tmp[] = 1;
		} else {
			$status_tmp[] = 0;
		}
	}

	$reps = array_merge($reps, $reps_tmp);
	$names = array_merge($names, $names_tmp);
	$posts = array_merge($posts, $posts_tmp);
	$avatars = array_merge($avatars, $avatars_tmp);
	$userid = array_merge($userid, $userid_tmp);
	$status = array_merge($status, $status_tmp);

	// Update join dates into UNIX timestamp
	foreach ($joins as &$join) {
		$join = strtotime($join);
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
		addEntry($userid[$c], $names[$c], $date, ($cycle+1), $avatars[$c], $posts[$c], $reps[$c], $status[$c]);
		calculateTotals($userid[$c]);
	}
    echo "Fetched, saved, and updated " . ($a+1)*20 . " / " . PAGES*20 . " member profiles\t(" . round((($a+1)/PAGES)*100,2 ) . "%)\n";
}
?>
