<?php
require_once("functions.php");
echo "<pre>";

$history = getHistory(username_to_id(fixUsername($_GET['user'])), getLastCycle());
$history = array_reverse($history);
$past = 0;
echo '<div align="center">';
echo "<img src='" . getVal(username_to_id(fixUsername($_GET['user'])), "avatar") . "' width='25' height='25'> - <font size='7'>" . fixUsername($_GET['user']) . "</font>\n";
foreach ($history as &$day) {
	echo date("F j, Y", ($day[date]-10000)) . "\t";
	$current = $day[rank];
	if ($current < $past) {
		$s = "<img src='images/green_up_arrow.png'>";
	} else if ($current > $past) {
		$s = "<img src='images/red_down_arrow.png'>";
	} else {
		$s = "<img src='images/blue_equal.png'>";
	}
	echo $s . $current . "\n";
	$past = $day[rank];
}
echo '</div>';
?>