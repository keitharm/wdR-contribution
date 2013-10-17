<?php
set_time_limit(0);
require_once("../functions.php");

# Base URL
define("URL", "http://webdevrefinery.com/forums/members/?sort_key=joined&sort_order=asc&max_results=20&st=");
# Number of pages to extract
define("PAGES", 547);

echo "--------------Settings--------------\n";
echo "Pages to fetch: \t\t" . PAGES . "\n";
echo "Total expected results: \t" . PAGES*20 . "\n";

// Fetch and extract data
echo "\nFetching data from wdR...\n";
for ($a = 0; $a < PAGES; $a++) {
	$reps = array();
	$posts = array();
	$userid = array();

	$data = file_get_contents(URL . $a*20);

	$reps_tmp  = extractData($data, "<span class='number'>", "</span>");
	$posts_tmp = extractData($data, "</span><span class='left'>", "</span>");
	$userid_tmp = extractData($data, "<strong><a href='", "' title='View Profile'>");

	foreach ($userid_tmp as &$user_idv) {
		$user_idv = extractData($user_idv, "user/", "-");
	}

	$reps = array_merge($reps, $reps_tmp);
	$posts = array_merge($posts, $posts_tmp);
	$userid = array_merge($userid, $userid_tmp);

	for ($b = 0; $b < 20; $b++) {
		addUserBase($userid[$b], $posts[$b], $reps[$b]);
	}
    echo "Fetched and saved " . ($a+1)*20 . " member profiles\t(" . round((($a+1)/PAGES)*100,2 ) . "%)\n";
}
?>
