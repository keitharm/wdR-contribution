<?php
require_once 'functions.php';

# Base URL
define("URL", "http://webdevrefinery.com/forums/members/?sort_key=posts&sort_order=desc&max_results=20&st=");
# Number of pages to extract
define("PAGES", 547);
# Sort data by (username, score, post, rep, join, ppd)
define("SORT", "join");
# Reverse results?
define("REVERSE", false);

echo "--------------Settings--------------\n";
echo "Pages to fetch: \t\t" . PAGES . "\n";
echo "Total expected results: \t" . PAGES*20 . "\n";
echo "Sorting by: \t\t\t" . SORT . "\n";
echo "Reverse sorting: \t\t" . (int)REVERSE . "\n";
sleep(1);

$reps = array();
$names = array();
$posts = array();
$joins = array();
$urls = array();
$avatars = array();

$scores = array();
$results = array();

// Fetch and extract data
echo "\nFetching data from wdR...\n";
for ($a = 0; $a < PAGES; $a++) {
	$data = file_get_contents(URL . $a*20);

	$reps_tmp  = extractData($data, "<span class='number'>", "</span>");
	$names_tmp = extractData($data, "View Profile'>", "</a>");
	$posts_tmp = extractData($data, "</span><span class='left'>", "</span>");
	$joins_tmp = extractData($data, "Joined:</span> ", "</span>");
	$avatars_tmp = extractData($data, "left'><img src='", "' alt=");
	$urls_tmp = extractData($data, "<strong><a href='", "' title='View Profile'>");

	$reps = array_merge($reps, $reps_tmp);
	$names = array_merge($names, $names_tmp);
	$posts = array_merge($posts, $posts_tmp);
	$joins = array_merge($joins, $joins_tmp);
	$avatars = array_merge($avatars, $avatars_tmp);
	$urls = array_merge($urls, $urls_tmp);
    echo "Fetched " . ($a+1)*20 . " member profiles\t(" . round((($a+1)/PAGES)*100,2 ) . "%)\n";
}

// Update join dates into UNIX timestamp
foreach ($joins as &$join) {
	$join = strtotime($join);
}

// Calculate scores with reputation * (posts/minutes since registration)
for ($a = 0; $a < (20*PAGES); $a++) {
	 $scores[] = ($reps[$a]*($posts[$a]/((time()-$joins[$a])/86400)));
	// Old algorithm
	//$scores[] = round(($reps[$a]/$posts[$a])*100, 2);
}

// Combine into nice multidimensional array for sorting
for ($a = 0; $a < (20*PAGES); $a++) {
	$results[$a][username] = $names[$a];
	$results[$a][score] = $scores[$a];
	$results[$a][post] = $posts[$a];
	$results[$a][rep] = $reps[$a];
	$results[$a][join] = $joins[$a];
	$results[$a][url] = $urls[$a];
	$results[$a][avatar] = $avatars[$a];
}

// Sort by score
usort($results, "sort_by_" . SORT);
if (REVERSE) {
    $results = array_reverse($results);
}


$rank = 0;
for ($a = 0; $a < (20*PAGES); $a++) {
	if (!userExists($results[$a][username])) {
		// Add user
		addUser($results[$a][username], round($results[$a][score], 2), $results[$a][post], $results[$a][rep], $results[$a][join], round($results[$a][post]/((time()-$results[$a][join])/86400), 2), $results[$a][url], $results[$a][avatar]);
	} else {
		// Update user
		updateUser($results[$a][username], round($results[$a][score], 2), $results[$a][post], $results[$a][rep], $results[$a][join], round($results[$a][post]/((time()-$results[$a][join])/86400), 2), $results[$a][url], $results[$a][avatar]);
	}
}
?>
