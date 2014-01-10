<?php
# Constant time since contribution started
define("START_TIME", 1382245200);
# Base URL
define("URL", "http://webdevrefinery.com/forums/members/?sort_key=posts&sort_order=desc&max_results=20&st=");
# Approximate ratio of how many members have more than 5 posts
define("POSTS_RATIO", .0722);

# Database info
if (exec("hostname") == "vps.toequest.com") {
        $config[db][dbname] = "code_wdr";
        $config[db][username] = "code_wdr";
        $config[db][password] = "totallynotmyactualpassword:)!";
} else {
	$config['db']['dbname'] = "wdr-contribution";
	$config['db']['username'] = "root";
	$config['db']['password'] = "wdrcontribmysql";
}
?>
