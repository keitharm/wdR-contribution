<?php
# Base URL
define(URL, "http://webdevrefinery.com/forums/members/?sort_key=posts&sort_order=desc&max_results=20&st=");
# Number of pages to extract
define(PAGES, 3);
# Minimum post count
define(MIN_POST, 100);
# Minimum reputation
define(MIN_REP, 10);
# Sort data by (username, score, post, rep, join, ppd)
define(SORT, "score");
# Reverse results?
define(REVERSE, false);

echo "--------------Settings--------------\n";
echo "Pages to fetch: \t\t" . PAGES . "\n";
echo "Total expected results: \t" . PAGES*20 . "\n";
echo "Minimum required posts: \t" . MIN_POST . "\n";
echo "Minimum required reputation: \t" . MIN_REP . "\n";
echo "Sorting algorithm: \t\t" . SORT . "\n";
echo "Reverse sorting: \t\t" . (int)REVERSE . "\n";
sleep(1);

$reps = array();
$names = array();
$posts = array();
$joins = array();

$scores = array();
$results = array();

// Fetch and extract data
echo "\nFetching data from wdR...\n";
for ($a = 0; $a < PAGES; $a++) {
	$data = file_get_contents(URL . $a*20);

	$reps_tmp  = extractData($data, "<span class='number'>");
	$names_tmp = extractData($data, "View Profile'>");
	$posts_tmp = extractData($data, "</span><span class='left'>");
	$joins_tmp = extractData($data, "Joined:</span> ");

	$reps = array_merge($reps, $reps_tmp);
	$names = array_merge($names, $names_tmp);
	$posts = array_merge($posts, $posts_tmp);
	$joins = array_merge($joins, $joins_tmp);
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

// Output results
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
}

// Sorting functions//
function sort_by_username($a, $b) {
    return strcmp($a["username"], $b["username"]);
}

function sort_by_score($a, $b) {
    return ($b[score]*100) - ($a[score]*100);
}

function sort_by_post($a, $b) {
    return ($b[post]) - ($a[post]);
}

function sort_by_rep($a, $b) {
    return ($b[rep]) - ($a[rep]);
}

function sort_by_join($a, $b) {
    return ($a[join]) - ($b[join]);
}

function sort_by_ppd($a, $b) {
    return (round($b[post]/((time()-$b[join])/86400), 2)*100) - (round($a[post]/((time()-$a[join])/86400), 2)*100);
}
//////////////////////

function extractData($data, $search) {
	$matches = findall($search, $data);
	foreach ($matches as &$val) {
		$offset = 0;
		$val += strlen($search);
		while ($data[$val+$offset] != "<") {
			$offset++;
		}
		$val = substr($data, $val, $offset);
	}
	return $matches;
}

// Function I found online
function findall($needle, $haystack) 
{ 
    //Setting up 
    $buffer=''; //We will use a 'frameshift' buffer for this search 
    $pos=0; //Pointer 
    $end = strlen($haystack); //The end of the string 
    $getchar=''; //The next character in the string 
    $needlelen=strlen($needle); //The length of the needle to find (speeds up searching) 
    $found = array(); //The array we will store results in 
    
    while($pos<$end)//Scan file 
    { 
        $getchar = substr($haystack,$pos,1); //Grab next character from pointer 
        if($getchar!="\\n" || buffer<$needlelen) //If we fetched a line break, or the buffer is still smaller than the needle, ignore and grab next character 
        { 
            $buffer = $buffer . $getchar; //Build frameshift buffer 
            if(strlen($buffer)>$needlelen) //If the buffer is longer than the needle 
            { 
                $buffer = substr($buffer,-$needlelen);//Truncunate backwards to needle length (backwards so that the frame 'moves') 
            } 
            if($buffer==$needle) //If the buffer matches the needle 
            { 
                $found[]=$pos-$needlelen+1; //Add the location of the needle to the array. Adding one fixes the offset. 
            } 
        } 
        $pos++; //Increment the pointer 
    } 
    if(array_key_exists(0,$found)) //Check for an empty array 
    { 
        return $found; //Return the array of located positions 
    } 
    else 
    { 
        return false; //Or if no instances were found return false 
    } 
}