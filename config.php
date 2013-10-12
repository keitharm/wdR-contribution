<?php
# Database info
if (exec("hostname") == "vps.toequest.com") {
	$config[db][dbname] = "code_wdr";
	$config[db][username] = "code_wdr";
	$config[db][password] = "totallynotmyactualpassword:)!";
} else {
	$config['db']['dbname'] = "wdr-contribution";
	$config['db']['username'] = "root";
	$config['db']['password'] = "";
}
?>
