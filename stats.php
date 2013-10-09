<?php
require_once 'functions.php';

$user = "sole_wolf";
$info = getUserData($user);
# Base URL
$url = file_get_contents($info->url);

// Fetch and extract data
echo "\nFetching data from wdR...\n";

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

$data = array("Username" => $user, "Group" => searchForWordsInString(extractData($url, "<span class='row_data'>", "</span>", 1), array("Administrators", "Members", "Mini Mod", "Moderators", "Noneditors", "Staff In Review", "Validating")),
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
print_r($data);
?>