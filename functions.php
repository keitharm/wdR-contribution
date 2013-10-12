<?php
// Configuration values for DB connection
require_once('config.php');

// All Functions below this line //
function database() {
    global $config;
    $db = new PDO("mysql:host=localhost;port=3306;dbname=" . $config['db']['dbname'], $config['db']['username'], $config['db']['password']);
    return $db;
}

// Sorting functions
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

function addUser($username, $score, $posts, $reputation, $joindate, $ppd, $url, $avatar) {
    $db = database();
    $statement = $db->prepare("INSERT INTO `users` (`username`, `score`, `posts`, `reputation`, `joindate`, `ppd`, `url`, `avatar`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $statement->execute(array($username, $score, $posts, $reputation, $joindate, $ppd, $url, $avatar));
}

function updateUser($username, $score, $posts, $reputation, $joindate, $ppd, $url, $avatar) {
    $db = database();
    $statement = $db->prepare("UPDATE `users` SET `score` = ?, `posts` = ?, `reputation` = ?, `joindate` = ?, `ppd` = ?, `url` = ?, `avatar` = ? WHERE `username` = ?");
    $statement->execute(array($score, $posts, $reputation, $joindate, $ppd, $url, $avatar, $username));
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

function userStats($username) {
    $info = getUserData($username);

    # User's profile URL
    $url = file_get_contents($info->url);

    $offset = 0;
    if (strpos($url, "Member Title") !== false) {
        $member_title = extractData($url, "<span class='row_data'>", "</span>", 4);
    } else {
        $member_title = "-";
        $offset = 1;
    }

    $lastactive = extractData($url, "Last Active ", "</span>");
    if ($lastactive == "<i>Private</i>") {
        $lastactive = "Unknown";
    }

    $data = array("Username" => $username, "Group" => searchForWordsInString(extractData($url, "<span class='row_data'>", "</span>", 1), array("Administrators", "Members", "Mini Mod", "Moderators", "Noneditors", "Staff In Review", "Validating")),
     "Active Posts" => extractData($url, "<span class='row_data'>", "</span>", 2),
      "Profile Views" => extractData($url, "<span class='row_data'>", "</span>", 3),
       "Member Title" => $member_title,
        "Age" => extractData($url, "<span class='row_data'>", "</span>", (5-$offset)),
         "Birthday" => extractData($url, "<span class='row_data'>", "</span>", (6-$offset)),
          "Reputation" => extractData($url, "<span class='number'>", "</span>"),
          "Join Date" => extractData($url, "Member Since ", "<br />"),
          "Last Active" => $lastactive,
          "Status" => searchForWordsInString(extractData($url, "<span class='ipsBadge", "</span>"), array("Online", "Offline")));

    if ($data["Age"] == null) {
        $data["Age"] = "Unknown";
    }

    if ($data["Birthday"] == null) {
        $data["Birthday"] = "Unknown";
    }

    // Fix for Age being hidden
    if (strlen($data["Birthday"]) > 20) {
        $data["Birthday"] = $data["Age"];
        $data["Age"] = "Unknown";
    }
    return $data;
}

function getUserOnlineState($username) {
    $info = getUserData($username);

    # User's profile URL
    $url = file_get_contents($info->url);

    $data = array("Status" => searchForWordsInString(extractData($url, "<span class='ipsBadge", "</span>"), array("Online", "Offline")));

    return $data;
}

function getUserRank($username) {
    $db = database();
    $statement = $db->query("SELECT * FROM users ORDER BY score desc LIMIT 100");
    $statement->setFetchMode(PDO::FETCH_ASSOC);

    $rank = 1;
    while ($row = $statement->fetch()) {
        if (strtolower($row["username"]) == strtolower($username)) {
            return $rank;
        } else {
            $rank++;
        }
    }
}

function fixUsername($username) {
    $data = getUserData($username);
    return $data->username;
}
?>