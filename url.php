<?php
require_once 'functions.php';

# Base URL
define("URL", "http://webdevrefinery.com/forums/members/?sort_key=joined&sort_order=desc&max_results=20&st=");
# Number of pages to extract
define("PAGES", 547);
# Minimum post count

echo "--------------Settings--------------\n";
echo "Pages to fetch: \t\t" . PAGES . "\n";
echo "Total expected results: \t" . PAGES*20 . "\n";
sleep(1);

$names = array();
$urls = array();
$results = array();
$joins = array();

// Fetch and extract data
echo "\nFetching data from wdR...\n";
for ($a = 0; $a < PAGES; $a++) {
	$data = file_get_contents(URL . $a*20);

	$names_tmp = extractData($data, "View Profile'>", "</a>");
	$names = array_merge($names, $names_tmp);

	$urls_tmp = extractData($data, "<strong><a href='", "' title='View Profile'>");
	$urls = array_merge($urls, $urls_tmp);

	$joins_tmp = extractData($data, "Joined:</span> ", "</span>");
	$joins = array_merge($joins, $joins_tmp);
    echo "Fetched " . ($a+1)*20 . " member profiles\t(" . round((($a+1)/PAGES)*100,2 ) . "%)\n";
}

// Update join dates into UNIX timestamp
foreach ($joins as &$join) {
	$join = strtotime($join);
}

// Combine into nice multidimensional array for sorting
for ($a = 0; $a < (20*PAGES); $a++) {
	$results[$a][username] = $names[$a];
	$results[$a][url] = $urls[$a];
	$results[$a][join] = $joins[$a];
}

// Sort by score
usort($results, "sort_by_join");


// Output results and insert/update database
echo "\nResults:\n\n";
echo "Username\t\tURL\n";
echo "--------\n";

for ($a = 0; $a < (20*PAGES); $a++) {

	echo $results[$a][username] . "\t" . $results[$a][url] . "\n";


	if (!userExists($results[$a][username])) {
		// Add user
		//addUser($results[$a][username], round($results[$a][score], 2), $results[$a][post], $results[$a][rep], $results[$a][join], round($results[$a][post]/((time()-$results[$a][join])/86400), 2));
		addURLUser($results[$a][username], $results[$a][url]);
	} else {
		// Update user
		//updateUser($results[$a][username], round($results[$a][score], 2), $results[$a][post], $results[$a][rep], $results[$a][join], round($results[$a][post]/((time()-$results[$a][join])/86400), 2));
		updateURLUser($results[$a][username], $results[$a][url]);
	}
}
?>