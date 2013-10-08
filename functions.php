<?php
# Database info
$config[name] = "code_wdr";
$config[user] = "code_wdr";
$config[pass] = "totallynotmyactualpassword:)!";

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

function extractData($data, $search, $ending, $specific = -1) {
	$matches = findall($search, $data);
	foreach ($matches as &$val) {
		$offset = 0;
		$val += strlen($search);
        while (substr($data, $val+$offset, strlen($ending)) != $ending) {
            $offset++;
        }
		$val = substr($data, $val, $offset);
	}
    if ($matches == false) {
        return "Error, no matches found.";
    }

    if ($specific == -1) {
        if (count($matches) == 1) {
            return $matches[0];
        }
	    return $matches;
    }
    return $matches[$specific-1];
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
    global $config;
    $db = new PDO("mysql:host=localhost;port=3306;dbname=" . $config[name], $config[user], $config[pass]);
    return $db;
}

function userExists($username) {
    $db = database();
    $statement = $db->prepare("SELECT * FROM users WHERE `username` = ?");
    $statement->execute(array($username));
    $info = $statement->FetchObject();
    if ($info != null) {
        return 1;
    } else {
        return 0;
    }
}

function getUserData($username) {
    $db = database();
    $statement = $db->prepare("SELECT * FROM `users` WHERE `username` = ?");
    $statement->execute(array($username));
    $info = $statement->FetchObject();

    return $info;
}

function addUser($username, $score, $posts, $reputation, $joindate, $ppd, $url) {
    $db = database();
    $statement = $db->prepare("INSERT INTO `users` (`username`, `score`, `posts`, `reputation`, `joindate`, `ppd`, `url`) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $statement->execute(array($username, $score, $posts, $reputation, $joindate, $ppd, $url));
}

function updateUser($username, $score, $posts, $reputation, $joindate, $ppd, $url) {
    $db = database();
    $statement = $db->prepare("UPDATE `users` SET `score` = ?, `posts` = ?, `reputation` = ?, `joindate` = ?, `ppd` = ?, `url` = ? WHERE `username` = ?");
    $statement->execute(array($score, $posts, $reputation, $joindate, $ppd, $url, $username));
}

function addURLUser($username, $url) {
    $db = database();
    $statement = $db->prepare("INSERT INTO `users` (`username`, `url`) VALUES (?, ?)");
    $statement->execute(array($username, $url));
}

function updateURLUser($username, $url) {
    $db = database();
    $statement = $db->prepare("UPDATE `users` SET `url` = ? WHERE `username` = ?");
    $statement->execute(array($url, $username));
}

// LOL!
function searchForWordsInString($data, $values) {
    $found = array();
    foreach($values as $val) {
        if (strpos($data, $val) !== false) {
            $found[] = $val;
        }
    }

    if (count($found) == 0) {
        return "No Matches Found";
    }
    else if (count($found) == 1) {
        return $found[0];
    } else {
        return "Multiple Matches Found";
    }
}
?>