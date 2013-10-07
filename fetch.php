<?php
require_once 'functions.php';

# Base URL
define("URL", "http://webdevrefinery.com/forums/members/?sort_key=posts&sort_order=desc&max_results=20&st=");
# Number of pages to extract
define("PAGES", 10);
# Minimum post count
define("MIN_POST", 100);
# Minimum reputation
define("MIN_REP", 10);
# Sort data by (username, score, post, rep, join, ppd)
define("SORT", "score");
# Reverse results?
define("REVERSE", false);

echo "--------------Settings--------------\n";
echo "Pages to fetch: \t\t" . PAGES . "\n";
echo "Total expected results: \t" . PAGES*20 . "\n";
echo "Minimum required posts: \t" . MIN_POST . "\n";
echo "Minimum required reputation: \t" . MIN_REP . "\n";
echo "Sorting by: \t\t\t" . SORT . "\n";
echo "Reverse sorting: \t\t" . (int)REVERSE . "\n";
sleep(1);

$reps = array();
$names = array();
$posts = array();
$joins = array();
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
	$avatars_tmp = extractData($data, "class='ipsUserPhotoLink left'><img src='", "' alt=");

	$reps = array_merge($reps, $reps_tmp);
	$names = array_merge($names, $names_tmp);
	$posts = array_merge($posts, $posts_tmp);
	$joins = array_merge($joins, $joins_tmp);
	$avatars = array_merge($avatars, $avatars_tmp);
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
	$results[$a][avatar] = $avatars[$a];
}

// Sort by score
usort($results, "sort_by_" . SORT);
if (REVERSE) {
    $results = array_reverse($results);
}

$total_members = 0;
// Precalculate how many total members
for ($a = 0; $a < (20*PAGES); $a++) {
	// Skip all members with posts less than 100 or reputation less than 10
	if ($results[$a][post] < MIN_POST || $results[$a][rep] < MIN_REP) {
		continue;
	}
	$total_members++;
}

// Output results and insert/update database
echo "\nThe top " . $total_members . " most helpful members of webdevRefinery are:\n\n";
echo "      Username		Score		Posts		        Reputation	Join Date		PPD\n";
echo "------------------------------------------------------------------------------------------------------------\n";

$rank = 0;
for ($a = 0; $a < (20*PAGES); $a++) {
	// Skip all members with posts less than 100 or reputation less than 10
	if ($results[$a][post] < MIN_POST || $results[$a][rep] < MIN_REP) {
		continue;
	}

	// So long usernames don't force columns to be unaligned
	if (strlen($results[$a][username]) < 10) {
		$name = "\t";
	} else {
		$name = null;
	}

	echo str_pad(++$rank, 4, "#000", STR_PAD_LEFT) . ": " . $results[$a][username] . $name . "\t" . round($results[$a][score], 2) . "\t\t" . $results[$a][post] . "\t\t" . $results[$a][rep] . "\t\t" . date("m-d-Y", $results[$a][join]) . "\t\t" . round($results[$a][post]/((time()-$results[$a][join])/86400), 2) . "\n";

	if (!userExists($results[$a][username])) {
		// Add user
		addUser($results[$a][username], round($results[$a][score], 2), $results[$a][post], $results[$a][rep], $results[$a][join], round($results[$a][post]/((time()-$results[$a][join])/86400), 2));
	} else {
		// Update user
		updateUser($results[$a][username], round($results[$a][score], 2), $results[$a][post], $results[$a][rep], $results[$a][join], round($results[$a][post]/((time()-$results[$a][join])/86400), 2));
	}
}
?>