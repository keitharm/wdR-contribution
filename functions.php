<?php
// Configuration values
require_once("config.php");

// All functions below this line //
function database() {
    global $config;
    try {
        $db = new PDO("mysql:host=localhost;port=3306;dbname=" . $config['db']['dbname'], $config['db']['username'], $config['db']['password']);
        return $db;
    } catch (PDOException $e) {
        echo "Uh oh, something went wrong...";
        die;
    }
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

// Does the user exists in the total table
function userExists($userid) {
    $db = database();
    $statement = $db->prepare("SELECT * FROM `total` WHERE `userid` = ?");
    $statement->execute(array($userid));
    $info = $statement->FetchObject();
    if ($info != null) {
        return 1;
    }
    return 0;
}

// Does the user exists in the base table
function userExistsBase($userid) {
    $db = database();
    $statement = $db->prepare("SELECT * FROM `base` WHERE `userid` = ?");
    $statement->execute(array($userid));
    $info = $statement->FetchObject();
    if ($info != null) {
        return 1;
    }
    return 0;
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

// Fetch the user's real time stats from wdR
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

// Fix capitalization and case of username
function fixUsername($username) {
    $userid = username_to_id($username);
    $data = getTotal($userid);
    if ($data == null) {
        return null;
    }
    return $data->username;
}

// Add latest stats for that day into history table
function addEntry($userid, $username, $date, $cycle, $avatar, $posts, $reputation, $loggedon) {
    // current - total - base gets daily difference
    $posts = $posts - getTotal($userid, "posts") - getBase($userid, "posts");
    $reputation = $reputation - getTotal($userid, "reputation") - getBase($userid, "reputation");
    $points = $posts*5 + $reputation*35 + $loggedon*1;

    // Detect if user logged in and their activity status is set to private
    if ($posts != 0) {
        $loggedon = 1;
    }

    $db = database();
    $statement = $db->prepare("INSERT INTO `history` (`userid`, `username`, `date`, `cycle`, `avatar`, `points`, `posts`, `reputation`, `loggedon`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $statement->execute(array($userid, $username, $date, $cycle, $avatar, $points, $posts, $reputation, $loggedon));
}

// Get base values of a user
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

// Get user's last stats from history
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

// Fetch the user's data from total table
function getTotal($userid, $type = null) {
    $db = database();
    $statement = $db->prepare("SELECT * FROM `total` WHERE `userid` = ?");
    $statement->execute(array($userid));
    $info = $statement->FetchObject();

    if ($info == null) {
        return 0;
    }

    if ($type === null) {
        return $info;
    }
    return $info->$type;
}

// Get last cycle number
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

// Calculate user totals by adding up vals from history table
function calculateTotals($userid) {
    $db = database();
    $statement = $db->prepare("SELECT * FROM `history` WHERE `userid` = ? ORDER BY `cycle` DESC");
    $statement->execute(array($userid));

    $first = true;

    // actually defines the variables before beginning loop so that the loop has something to "add to"
    $points = 0;
    $posts = 0;
    $reputation = 0;
    $logins = 0;

    while ($info = $statement->FetchObject()) {
        // Get the most recent avatar and username
        if ($first) {
            $avatar = $info->avatar;
            $username = $info->username;
            $first = false;
        }
        // Add up totals
        $points += $info->points;
        $posts += $info->posts;
        $reputation += $info->reputation;
        $logins += $info->loggedon;
    }

    // Calculate PPD
    $timedif = ceil((time() - START_TIME) / 86400);
    $ppd = round($posts / $timedif, 2);

    $activityStatementQuery = $db->prepare("SELECT * FROM `history` WHERE `userid` = ? ORDER BY `cycle` DESC LIMIT 30");
    $activityStatementQuery->setFetchMode(PDO::FETCH_ASSOC);

    /**
     * Below code assigns a decreasing number of points depending on when the user last logged on.
     * 30 points for logging in yesterday, 29 for the day before that, etc.
     */

    $currentDayCycle = 30;
    $activityPoints = 0;
    while ($row = $activityStatementQuery->fetch()) {

        if ($row['loggedon']==1) {

            $activityPoints += 30 - (30 - $currentDayCycle);
            $currentDayCycle = $currentDayCycle - 1;

        }

    }

    // Percentage of total possible points for last 30 days (30 + 29 ... + 1) = 465
    $activity = round($activityPoints / 465, 4);

    // Calculate score
    $score = $activity * $points;

    // Update total values
    updateTotals($userid, $username, $score, $points, $posts, $reputation, $ppd, $avatar, $logins, $activity);
}

// How many cycles has a user been through
function totalCycles($userid) {
    $db = database();
    $statement = $db->prepare("SELECT * FROM `history` WHERE `userid` = ?");
    $statement->execute(array($userid));

    return $statement->rowCount();
}

// Update Total's table values
function updateTotals($userid, $username, $score, $points, $posts, $reputation, $ppd, $avatar, $logins, $activity) {
    $db = database();
    if (!userExists($userid)) {
        $statement = $db->prepare("INSERT INTO `total` (`userid`, `username`, `score`, `points`, `posts`, `reputation`, `ppd`, `avatar`, `logins`, `activity`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $statement->execute(array($userid, $username, $score, $points, $posts, $reputation, $ppd, $avatar, $logins, $activity));
    } else {
        $statement = $db->prepare("UPDATE `total` SET `username`=?, `score`=?, `points`=?, `posts`=?, `reputation`=?, `ppd`=?, `avatar`=?, `logins`=?, `activity`=? WHERE `userid`=?");
        $statement->execute(array($username, $score, $points, $posts, $reputation, $ppd, $avatar, $logins, $activity, $userid));
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

// Update rankings of users in total table
function updateRanks() {
    $db = database();
    $statement = $db->prepare("SELECT * FROM `total` ORDER BY `score` DESC");
    $statement->execute();

    $rank = 0;
    while ($info = $statement->fetchObject()) {
        changeVal($info->userid, "rank", ++$rank);
    }
}

// Update the ranks of users in the history table by changing the rank of the most recent cycle
function updateHistoryRanks() {
    $db = database();
    $statement = $db->prepare("SELECT * FROM `total` ORDER BY `score` DESC");
    $statement->execute();
    while ($info = $statement->fetchObject()) {
        changeHistoryVal($info->userid, "rank", $info->rank);
    }
}

// Change value of user in total table
function changeVal($userid, $fieldname, $value) {
    $db = database();
    $statement = $db->prepare("UPDATE `total` SET `$fieldname` = ? WHERE `userid` = ?");
    $statement->execute(array($value, $userid));
}

// Change value of user in history table
function changeHistoryVal($userid, $fieldname, $value) {
    $db = database();
    $statement = $db->prepare("UPDATE `history` SET `$fieldname` = ? WHERE `userid` = ? AND `cycle` = ?");
    $statement->execute(array($value, $userid, getLastCycle()));
}

// Get userval from total table
function getVal($userid, $fieldname) {
    $db = database();
    $statement = $db->prepare("SELECT * FROM `total` WHERE `userid` = ?");
    $statement->execute(array($userid));
    $info = $statement->fetchObject();

    return $info->$fieldname;
}

// Returns X number of days of stats history per user.
function getHistory($userid, $results = 5) {
    $db = database();
    $statement = $db->prepare("SELECT * FROM `history` WHERE `userid` = ? ORDER BY `cycle` DESC LIMIT $results");
    $statement->execute(array($userid));

    $entry = 0;
    while ($info = $statement->fetchObject()) {
        $data[$entry]["username"] = $info->username;
        $data[$entry]["date"] = $info->date;
        $data[$entry]["cycle"] = $info->cycle;
        $data[$entry]["avatar"] = $info->avatar;
        $data[$entry]["rank"] = $info->rank;
        $data[$entry]["points"] = $info->points;
        $data[$entry]["posts"] = $info->posts;
        $data[$entry]["reputation"] = $info->reputation;
        $data[$entry]["loggedon"] = $info->loggedon;
        ++$entry;
    }
    return $data;
}

// Return rank change of history vs current
function getRankChange($userid, $num = false) {
    $history = getHistory($userid, 1); 
    if (getUpdateDate() == "stats are currently being updated...") {
        return "--";
    }

    if ($history == null || count($history) < 1) {
        return "--";
    }

    $past = $history[0]['rank'];
    $current = getVal($userid, "rank");

    // Rank went up
    if ($current < $past || $past == 0) {
        // They just joined the list
        if ($past == 0) {
            if ($num) {
                return (totalUsers()-$current);
            }
            return "<img src='images/green_up_arrow.png'>" . (totalUsers()-$current);
        }
        if ($num) {
            return ($past-$current);
        }
        return "<img src='images/green_up_arrow.png'>" . ($past-$current);
    }
    // Rank went down
    else if ($current > $past) {
        if ($num) {
            return -($current-$past);
        }
        return "<img src='images/red_down_arrow.png'>" . ($current-$past);
    } else {
        if ($num) {
            return 0;
        }
        return "--";
    }
}

// Return post change of history vs current
function getPostChange($userid, $sign = true) {
    $history = getHistory($userid, 1); 
    if (getUpdateDate() == "stats are currently being updated...") {
        return "";
    }

    if ($history == null || count($history) < 1) {
        return "";
    }

    $past = $history[0]['posts'];
    if ($past != 0) {
        if ($sign == true) {
            return "<font color='#28D308'>+" . $past . "</font>";
        }
        return $past;
    }
}

// Return rep change of history vs current
function getRepChange($userid, $sign = true) {
    $history = getHistory($userid, 1); 
    if (getUpdateDate() == "stats are currently being updated...") {
        return "";
    }

    if ($history == null || count($history) < 1) {
        return "";
    }

    $past = $history[0]['reputation'];
    if ($past != 0) {
        if ($sign == true) {
            return "<font color='#28D308'>+" . $past . "</font>";
        }
        return $past;
    }
}

// Return point change of history vs current
function getPointsChange($userid, $sign = true) {
    $history = getHistory($userid, 1); 
    if (getUpdateDate() == "stats are currently being updated...") {
        return "";
    }

    if ($history == null || count($history) < 1) {
        return "";
    }

    $past = $history[0]['points'];
    if ($past != 0) {
        if ($sign == true) {
            return "<font color='#28D308'>+" . $past . "</font>";
        }
        return $past;
    }
}

function getUpdateDate() {
    $db = database();
    $statement = $db->prepare("SELECT COUNT(*) FROM `history` WHERE `cycle` = ?");
    $statement->execute(array(getLastCycle()));

    $info = $statement->fetchAll();
    $cycleTotal = $info[0][0];
    $total = totalUsers();

    if ($cycleTotal != $total) {
        return "stats are currently being updated...";
    }

    $db = database();
    $statement = $db->prepare("SELECT `date` FROM history ORDER BY cycle DESC, `date` DESC LIMIT 1");
    $statement->execute();
    $info = $statement->fetchObject();

    return timeconv($info->date);
}

function timeconv($timestamp, $about = false) {
    $elapsed = time() - $timestamp;

    if ($about == true) {
        $about = "About ";
    }
    if ($elapsed == 0) {
        $data = "Just now";
    }

    // Seconds
    else if ($elapsed < 60) {
        if ($elapsed != 1) {
            $s = "s";
        }
        $data = $about . $elapsed . " second" . $s . " ago";
    }

    // Minutes
    else if ($elapsed < 60*60) {
        if ($elapsed >= 60*2) {
            $s = "s";
        }
        $data = $about . floor($elapsed/60) . " minute" . $s . " ago";
    }

    // Hours
    else if ($elapsed < 60*60*24) {
        if ($elapsed >= 60*60*2) {
            $s = "s";
        }
        $data = $about . floor($elapsed/(60*60)) . " hour" . $s . " ago";
    }
    
    // Days
    else if ($elapsed < 60*60*24*7) {
        if ($elapsed >= 60*60*24*2) {
            $s = "s";
        }
        $data = $about . floor($elapsed/(60*60*24)) . " day" . $s . " ago";
    } else {
        $data = date("m-d-Y", $timestamp);
    }

    return "<span title='" . date("F j, Y, g:i a", $timestamp) . "'>" . $data . "</span>";
}

// For ETAs and PHP5-less servers
function timer() {
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

// Number of pages in wdR members list
function numOfPages() {
    $tmp_data = file_get_contents(URL);
    $numofpages = extractData($tmp_data, "Page 1 of ", " <!--<img", 1);

    return $numofpages;
}

// Average of values in array
function array_avg($array) {
    return round(array_sum($array)/count($array), 2);
}

function totalUsers() {
    $db = database();
    $statement = $db->prepare("SELECT COUNT(*) FROM total");
    $statement->execute();
    $info = $statement->fetchAll();

    return $info[0][0];
}

function fixPage($page) {
    // Total Pages of users
    $total = ceil(totalUsers()/25);

    if ($page < 1 || $page == null) {
        $page = 1;
    }

    if ($page > $total) {
        $page = $total;
    }

    return (($page-1)*25);
}

function rankColor($rank) {
    switch ($rank) {
        case 1:
            return "<font color='gold'>1</font>";
        case 2:
            return "<font color='silver'>2</font>";
        case 3:
            return "<font color='saddlebrown'>3</font>";
        default:
            return $rank;
    }
}

function pageControls($page) {
    $total = ceil(totalUsers()/25);

    if ($page == null) {
        $page = 1;
    }
    if ($page < 1) {
        $page = 1;
    }
    if ($page > $total) {
        $page = $total;
    }

    $main = "Page " . ($page) . " of " . $total;

    // Default non-links
    $prev = "<";
    $prevprev = "<<";
    $next = ">";
    $nextnext = ">>";

    if ($page >= 2) {
        $prev = "<a href='index.php?page=" . ($page-1) . "'><</a>";
    }
    if ($page >= 3) {
        $prevprev = "<a href='index.php?page=1'><<</a>";
    }
    if ($total - $page >= 1) {
        $next = "<a href='index.php?page=" . ($page+1) . "'>></a>";
    }
    if ($total - $page >= 2) {
        $nextnext = "<a href='index.php?page=" . $total . "'>>></a>";
    }
    $main = $prevprev . "&nbsp;&nbsp;" . $prev . "&nbsp;&nbsp;" . $main . "&nbsp;&nbsp;" . $next . "&nbsp;&nbsp;" . $nextnext;
    return $main;
}

function userColor($id, $username) {
    if (in_array($id, array(1, 2))) {
        return "<font color='red'><b>" . $username . "</b></font>";
    } else if (in_array($id, array(602, 3291, 3008, 5574, 4637))) {
        return "<font color='#f94'><b>" . $username . "</b></font>";
    } else {
        return $username;
    }
}

function statsLastXDays($type, $days) {
    $str = "[";

    for ($i = $days; $i > 0; $i--) {
        if ($type == "day") {
            $str .= "'" . date("m.d", strtotime(date("m.d.y"))-($i*86400)) . "', ";
            //$str .= $i . ", ";
        } else if ($type == "posts") {
            $str .= totalXDaysAgo("posts", $i) . ", ";
        } else if ($type == "reputation") {
            $str .= totalXDaysAgo("reputation", $i) . ", ";
        } else if ($type == "loggedon") {
            $str .= totalXDaysAgo("loggedon", $i) . ", ";
        } else if ($type == "points") {
            $str .= totalXDaysAgo("points", $i)/10 . ", ";
        } else {
            $str .= "";
        }
    }

    return substr($str, 0, -2) . "]";
}

function totalXDaysAgo($type, $days) {
    $db = database();
    $statement = $db->prepare("SELECT $type FROM `history` WHERE `cycle` = ? ORDER BY cycle DESC");
    $statement->execute(array(getLastCycle()+1-$days));
    $total = 0;

    while ($info = $statement->fetchObject()) {
        $total += $info->$type;
    }

    return $total;
}

function userStatsLastXDays($type, $days, $userid = 1) {
    $str = "[";

    for ($i = $days; $i > 0; $i--) {
        if ($type == "day") {
            $str .= "'" . date("m.d", strtotime(date("m.d.y"))-($i*86400)) . "', ";
            //$str .= $i . ", ";
        } else if ($type == "posts") {
            $str .= userTotalXDaysAgo("posts", $i, $userid) . ", ";
        } else if ($type == "reputation") {
            $str .= userTotalXDaysAgo("reputation", $i, $userid) . ", ";
        } else if ($type == "loggedon") {
            $str .= userTotalXDaysAgo("loggedon", $i, $userid) . ", ";
        } else if ($type == "points") {
            $str .= userTotalXDaysAgo("points", $i, $userid)/10 . ", ";
        } else if ($type == "rank") {
            $str .= userTotalXDaysAgo("rank", $i, $userid) . ", ";
        } else {
            $str .= "";
        }
    }

    return substr($str, 0, -2) . "]";
}

function userTotalXDaysAgo($type, $days, $userid) {
    $db = database();
    $statement = $db->prepare("SELECT $type FROM `history` WHERE `cycle` = ? AND userid = ? ORDER BY cycle DESC");
    $statement->execute(array(getLastCycle()+1-$days, $userid));
    $total = 0;

    $info = $statement->fetchObject();
    return $info->$type;
}

function avgStats($data) {
    $split = explode(", ", substr($data, 1, -1));
    return array_avg($split);
}

function doesExist($table, $fieldname, $value) {
    $db = database();
    $statement = $db->prepare("SELECT * FROM $table WHERE $fieldname = ?");
    $statement->execute(array($value));
    $info = $statement->FetchObject();
    if ($info != null) {
        return 1;
    } else {
        return 0;
    }
}

function updateCache() {
    for ($i = 30; $i > 0; $i--) {
        updateCacheValue($i, "posts", totalXDaysAgo("posts", $i));
        updateCacheValue($i, "reputation", totalXDaysAgo("reputation", $i));
        updateCacheValue($i, "loggedon", totalXDaysAgo("loggedon", $i));
        updateCacheValue($i, "points", round(totalXDaysAgo("points", $i)/10, 1));
    }
}

function updateCacheValue($day, $field, $value) {
    $db = database();
    $statement = $db->prepare("UPDATE `cache` SET $field = ? WHERE `day` = ?;");
    $statement->execute(array($value, $day));
}

function fetchCacheValue($day, $field) {
    $db = database();
    $statement = $db->prepare("SELECT $field FROM `cache` WHERE `day` = ?");
    $statement->execute(array($day));
    $info = $statement->fetchObject();
    return $info->$field;
}

function fetchCached($field) {
    $str = "[";
    for ($i = 30; $i > 0; $i--) {
        if ($field == "posts") {
            $str .= fetchCacheValue($i, "posts") . ", ";
        } else if ($field == "reputation") {
            $str .= fetchCacheValue($i, "reputation") . ", ";
        } else if ($field == "loggedon") {
            $str .= fetchCacheValue($i, "loggedon") . ", ";
        } else if ($field == "points") {
            $str .= fetchCacheValue($i, "points") . ", ";
        }
    }

    return substr($str, 0, -2) . "]";
}

function updateLeaderboardsCache() {
    $db = database();
    $statement = $db->query("SELECT * FROM `total` ORDER BY `rank` ASC");
    while ($row = $statement->fetch()) {
        updateLeaderboardsCacheValue($row["userid"], "rank_change", getRankChange($row["userid"], true));
        updateLeaderboardsCacheValue($row["userid"], "point_change", round(getPointsChange($row["userid"], false)*$row["activity"]));
        updateLeaderboardsCacheValue($row["userid"], "post_change", (int)getPostChange($row["userid"], false));
        updateLeaderboardsCacheValue($row["userid"], "rep_change", (int)getRepChange($row["userid"], false));
    }
}

function updateLeaderboardsCacheValue($userid, $field, $value) {
    $db = database();
    if (doesExist("leaderboards_cache", "userid", $userid)) {
        $statement = $db->prepare("UPDATE `leaderboards_cache` SET $field = ? WHERE `userid` = ?;");
        $statement->execute(array($value, $userid));
    } else {
        $statement = $db->prepare("INSERT INTO `leaderboards_cache` (`userid`, `$field`) VALUES (?,?)");
        $statement->execute(array($userid, $value));
    }
}

function fetchLeaderboardsCacheValue($userid, $field) {
    $db = database();
    $statement = $db->prepare("SELECT $field FROM `leaderboards_cache` WHERE `userid` = ?");
    $statement->execute(array($userid));
    $info = $statement->fetchObject();
    return $info->$field;
}

function stylize($type, $value) {
    if ($type == "rank") {
        if ($value < 0) {
            return "<img src='images/red_down_arrow.png'>" . -$value;
        } else if ($value > 0) {
            return "<img src='images/green_up_arrow.png'>" . $value;
        } else {
            return "--";
        }
    } else {
        if ($value > 0) {
            return "<font color='#28D308'>+" . $value . "</font>";
        } else {
            return "";
        }
    }
}
?>
