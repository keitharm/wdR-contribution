<?php
// Configuration values for DB connection
require_once("config.php");

// All Functions below this line //
function database() {
    global $config;
    $db = new PDO("mysql:host=localhost;port=3306;dbname=" . $config['db']['dbname'], $config['db']['username'], $config['db']['password']);
    return $db;
}

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
    }
    return false;
}

function userExists($userid) {
    $db = database();
    $statement = $db->prepare("SELECT * FROM total WHERE `userid` = ?");
    $statement->execute(array($userid));
    $info = $statement->FetchObject();
    if ($info != null) {
        return 1;
    }
    return 0;
}

function getUserData($userid) {
    $db = database();
    $statement = $db->prepare("SELECT * FROM `total` WHERE `userid` = ?");
    $statement->execute(array($userid));
    $info = $statement->FetchObject();

    return $info;
}

function addUserBase($userid, $posts, $reputation) {
    $db = database();
    $statement = $db->prepare("INSERT INTO `base` (`userid`, `posts`, `reputation`) VALUES (?, ?, ?)");
    $statement->execute(array($userid, $posts, $reputation));
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

function userStats($userid) {
    # User's profile URL
    $url = file_get_contents("http://webdevrefinery.com/forums/user/" . $userid . "-");

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

    $data = array("Username" => id_to_username($userid), "Group" => searchForWordsInString(extractData($url, "<span class='row_data'>", "</span>", 1), array("Administrators", "Members", "Mini Mod", "Moderators", "Noneditors", "Staff In Review", "Validating")),
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

function getUserOnlineState($userid) {
    # User's profile URL
    $url = file_get_contents("http://webdevrefinery.com/forums/user/" . $userid . "-");
    $data = array("Status" => searchForWordsInString(extractData($url, "<span class='ipsBadge", "</span>"), array("Online", "Offline")));

    return $data;
}

function fixUsername($username) {
    $userid = username_to_id($username);
    $data = getUserData($userid);
    if ($data == null) {
        return null;
    }
    return $data->username;
}

function addEntry($userid, $username, $date, $cycle, $avatar, $posts, $reputation, $loggedon) {
    // current - total - base gets daily difference
    $posts = $posts - getTotal($userid, "posts") - getBase($userid, "posts");
    $reputation = $reputation - getTotal($userid, "reputation") - getBase($userid, "reputation");
    $score = $posts*10 + $reputation*25 + $loggedon*5;

    $db = database();
    $statement = $db->prepare("INSERT INTO `history` (`userid`, `username`, `date`, `cycle`, `avatar`, `score`, `posts`, `reputation`, `loggedon`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $statement->execute(array($userid, $username, $date, $cycle, $avatar, $score, $posts, $reputation, $loggedon));
}

function getBase($userid, $type) {
    $db = database();
    $statement = $db->prepare("SELECT * FROM `base` WHERE `userid` = ?");
    $statement->execute(array($userid));
    $info = $statement->FetchObject();

    if ($info == null) {
        return 0;
    }
    return $info->$type;
}

function getLast($userid, $type) {
    $db = database();
    $statement = $db->prepare("SELECT * FROM `history` WHERE `userid` = ? ORDER BY `cycle` DESC");
    $statement->execute(array($userid));
    $info = $statement->FetchObject();

    if ($info == null) {
        return 0;
    }
    return $info->$type;
}

function getTotal($userid, $type) {
    $db = database();
    $statement = $db->prepare("SELECT * FROM `total` WHERE `userid` = ?");
    $statement->execute(array($userid));
    $info = $statement->FetchObject();

    if ($info == null) {
        return 0;
    }
    return $info->$type;
}

function getLastCycle() {
    $db = database();
    $statement = $db->prepare("SELECT `cycle` FROM `history` ORDER BY `cycle` DESC LIMIT 1;");
    $statement->execute();
    $info = $statement->FetchObject();

    if ($info == null) {
        return 0;
    }
    return $info->cycle;
}

function calculateTotals($userid) {
    $db = database();
    $statement = $db->prepare("SELECT * FROM `history` WHERE `userid` = ? ORDER BY `cycle` DESC");
    $statement->execute(array($userid));

    $first = true;
    while ($info = $statement->FetchObject()) {
        // Get the most recent avatar and username
        if ($first) {
            $avatar = $info->avatar;
            $username = $info->username;
            $first = false;
        }
        // Add up totals
        $score += $info->score;
        $posts += $info->posts;
        $reputation += $info->reputation;
        $logins += $info->loggedon;
    }

    // Calculate PPD
    $timedif = ceil((time() - START_TIME) / 86400);
    $ppd = round($posts / $timedif, 2);

    // Update total values
    //echo "User id: " . $userid . "\nUsername: " . $username . "\nScore: " . $score . "Posts: " . $posts . "\nReputation: " . $reputation . "\nPPD: " . $ppd . "\navatar: " . $avatar . "\nLogins: " . $logins . "\n";
    updateTotals($userid, $username, $score, $posts, $reputation, $ppd, $avatar, $logins);
}

function updateTotals($userid, $username, $score, $posts, $reputation, $ppd, $avatar, $logins) {
    $db = database();
    if (!userExists($userid)) {
        $statement = $db->prepare("INSERT INTO `total` (`userid`, `username`, `score`, `posts`, `reputation`, `ppd`, `avatar`, `logins`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $statement->execute(array($userid, $username, $score, $posts, $reputation, $ppd, $avatar, $logins));
    } else {
        $statement = $db->prepare("UPDATE `total` SET `username`=?, `score`=?, `posts`=?, `reputation`=?, `ppd`=?, `avatar`=?, `logins`=? WHERE `userid`=?");
        $statement->execute(array($username, $score, $posts, $reputation, $ppd, $avatar, $logins, $userid));
    }
}

function username_to_id($username) {
    $db = database();
    $statement = $db->prepare("SELECT * FROM `total` WHERE `username` = ?");
    $statement->execute(array($username));
    $info = $statement->fetchObject();

    return $info->userid;
}

function id_to_username($userid) {
    $db = database();
    $statement = $db->prepare("SELECT * FROM `total` WHERE `userid` = ?");
    $statement->execute(array($userid));
    $info = $statement->fetchObject();

    return $info->username;
}
?>
