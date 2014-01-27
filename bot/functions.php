<?php
// Configuration values
require_once("config.php");

// All functions below this line //
function database() {
    global $config;
    $db = new PDO("mysql:host=localhost;port=3306;dbname=" . $config['db']['dbname'], $config['db']['username'], $config['db']['password']);
    return $db;
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

// Return rank change of history vs current
function getRankChange($userid) {
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
            return "<img src='../images/green_up_arrow.png'>" . (totalUsers()-$current);
        }
        return "<img src='../images/green_up_arrow.png'>" . ($past-$current);
    }
    // Rank went down
    else if ($current > $past) {
        return "<img src='../images/red_down_arrow.png'>" . ($current-$past);
    } else {
        return "--";
    }
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

function totalUsers() {
    $db = database();
    $statement = $db->prepare("SELECT COUNT(*) FROM total");
    $statement->execute();
    $info = $statement->fetchAll();

    return $info[0][0];
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

// Get userval from total table
function getVal($userid, $fieldname) {
    $db = database();
    $statement = $db->prepare("SELECT * FROM `total` WHERE `userid` = ?");
    $statement->execute(array($userid));
    $info = $statement->fetchObject();

    return $info->$fieldname;
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
            return "<font color='#28D308'>+" . $past;
        }
        return $past;
    }
}

// Return post change of history vs current
function getPostChange($userid) {
    $history = getHistory($userid, 1); 
    if (getUpdateDate() == "stats are currently being updated...") {
        return "";
    }

    if ($history == null || count($history) < 1) {
        return "";
    }

    $past = $history[0]['posts'];
    if ($past != 0) {
        return "<font color='#28D308'>+" . $past;
    }
}

// Return rep change of history vs current
function getRepChange($userid) {
    $history = getHistory($userid, 1); 
    if (getUpdateDate() == "stats are currently being updated...") {
        return "";
    }

    if ($history == null || count($history) < 1) {
        return "";
    }

    $past = $history[0]['reputation'];
    if ($past != 0) {
        return "<font color='#28D308'>+" . $past;
    }
}
?>