<?php
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

function extractData($data, $search, $ending) {
	$matches = findall($search, $data);
	foreach ($matches as &$val) {
		$offset = 0;
		$val += strlen($search);
        while (substr($data, $val+$offset, strlen($ending)) != $ending) {
            $offset++;
        }
		$val = substr($data, $val, $offset);
	}
	return $matches;
}

// Function I found online
// Rewrote it to look nicer (so many comments in the last version!)
function findall($needle, $haystack) { 
    $buffer = '';
    $pos = 0;
    $end = strlen($haystack);
    $getchar = '';
    $needlelen = strlen($needle); 
    $found = array();
    
    while ($pos < $end) { 
        $getchar = substr($haystack, $pos, 1);
        if ($getchar != "\\n" || $buffer < $needlelen) { 
            $buffer = $buffer . $getchar;
            if (strlen($buffer) > $needlelen) { 
                $buffer = substr($buffer, -$needlelen);
            }
            if ($buffer == $needle) { 
                $found[] = $pos - $needlelen + 1;
            } 
        } 
        $pos++;
    } 
    if (array_key_exists(0, $found)) { 
        return $found;
    } else { 
        return false;
    } 
}

function database() {
    $db = new PDO("mysql:host=localhost;port=3306;dbname=" . $db[name], $db[user], $db[pass]);
    return $db;
}
?>