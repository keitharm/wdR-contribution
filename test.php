<?php
echo "<pre>";
require_once 'functions.php';
//echo file_get_contents("default.jpg");
//print_r(getUserData($argv[1]));
$data = getUserData($_GET['user']);
$avatar = $data->avatar;
//echo $avatar . "\n";
if (strpos($avatar, "gravatar.com") !== false) {
	$avatar = substr($avatar, 0, 69);
	if (file_get_contents($avatar) == file_get_contents("default.jpg")) {
		$avatar = "http://i2.wp.com/webdevrefinery.com/forums/public/style_images/Cielo/profile/default_large.png";
	}
}
//echo $avatar;
echo "\n<img src='" . $avatar . "' width='100' height='100'>";
?>