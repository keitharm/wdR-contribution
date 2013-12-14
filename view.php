<?php
require_once("functions.php");

$userid = username_to_id(fixUsername($_GET['user']));

if ($userid == null) {
    die("The specified user does not exist.");
}

if (totalCycles($userid) > 30) {
    $days = 30;
} else {
    $days = totalCycles($userid);
}

define("DAYSBACK", $days);

$db = database();
$statement = $db->query("SELECT * FROM `history` WHERE `userid` = " . $userid . " ORDER BY `cycle` DESC LIMIT " . DAYSBACK);
$statement->setFetchMode(PDO::FETCH_ASSOC);
?>

<?php
    require_once("functions.php");

    $daysago = userStatsLastXDays("day", DAYSBACK, $userid);
    $posts = userStatsLastXDays("posts", DAYSBACK, $userid);
    $reps = userStatsLastXDays("reputation", DAYSBACK, $userid);
    $logins = userStatsLastXDays("loggedon", DAYSBACK, $userid);
    $points = userStatsLastXDays("points", DAYSBACK, $userid);
    $rank = userStatsLastXDays("rank", DAYSBACK, $userid);
?>
<!DOCTYPE html>
<html>
<head>
    <title>wdR Contribution | <?=fixUsername($_GET['user'])?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css" type="text/css">
    <script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
    <script type="text/javascript">
        $(function () {
            $('#container').highcharts({
                title: {
                    text: '<?=fixUsername($_GET[user])?>\'s stats history - Last <?=DAYSBACK?> days',
                    x: -20 //center
                },
                subtitle: {
                    text: 'Averages per day: <?=avgStats($posts)?> posts | <?=avgStats($reps)?> reputation points | <?=avgStats($logins)?> logins | <?=avgStats($points)*10?> points',
                    x: -20
                },
                xAxis: {
                    title: {
                        text: 'Date'
                    },
                    categories: <?=$daysago?>
                },
                yAxis: [{
                    min: 0,
                    title: {
                        text: 'Values'
                    },
                    plotLines: [{
                        value: 0,
                        width: 1,
                        color: '#808080'
                    }]
                }],
                legend: {
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'middle',
                    borderWidth: 0
                },
                series: [{
                    name: 'Posts',
                    data: <?=$posts?>,
                    color: '#0F0'
                }, {
                    name: 'Reputation Points',
                    data: <?=$reps?>,
                    color: '#F00'
                }, {
                    name: 'Logins',
                    data: <?=$logins?>,
                    color: '#00F'
                }, {
                    name: 'Points (normalized x/10)',
                    data: <?=$points?>,
                    color: '#F0F'
                }, {
                    name: 'Rank',
                    data: <?=$rank?>,
                    color: '#0FF'
                }],
                tooltip: {
                    shared: true
                }
            });
        });
    </script>
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="../../assets/js/html5shiv.js"></script>
    <script src="../../assets/js/respond.min.js"></script>
    <![endif]-->
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <a href='index.php'><h1>wdR Contribution</h1></a>
                <font size='4'>The</font> <font size='1'>un</font><font size='4'>Official wdR Contribution Tracker</font>
            </div>
            <div class="col-md-2">
            </div>
        </div>
        <div class="row">
            <script src="js/highcharts.js"></script>
            <div id="container" style="width: 100%; height: 250px; margin: 0 auto"></div>
        </div>
        <div class="row">
        	<h2 align='center'><?=fixUsername($_GET['user'])?>'s stats history (last <?=DAYSBACK?> days)</h2>
        </div>
        <div class="row">
            <table class="table table-hover" id="rank">
                <tr>
                    <th>Date</th>
                    <th>Rank</th>
                    <th>Username</th>
                    <th>Posts</th>
                    <th>Reputation</th>
                    <th>Logged on?</th>
                </tr>
                <?php
                    $users = 0;
                    while ($row = $statement->fetch()) {
                        echo "<tr>";
                        echo "<td align='center'>" . date("m.d.y", $row["date"]-3600) . "</td>";
						echo "<td align='center'>" . rankColor($row["rank"]) . "</td>";
                        echo "<td align='center'><img align='center' src='" . $row["avatar"] . "' width='25' height='25'>&nbsp;&nbsp;&nbsp;<a href='http://webdevrefinery.com/forums/user/{$row["userid"]}-{$row["username"]}'>" . userColor($row["userid"], $row["username"]) . "</a></td>";
                        echo "<td align='center'>" . $row["posts"] . "</td>";
                        echo "<td align='center'>" . $row["reputation"] . "</td>";
                        echo "<td align='center'><font color='" . ($row["loggedon"] == 1 ? "green'>yes" : "red'>no") . "</font></td>";
                        echo "</tr>";
                        $users++;
                    }
                    if ($users == 0) {
                        echo "<tr><td colspan='9' align='center'>User was not found</td></tr>";
                    }
                ?>
            </table>
        </div>
        <div class="row footer">
            Created and Designed by <a href="http://webdevrefinery.com/forums/user/2338-sole-wolf/">Sole_Wolf</a> and <a href="http://webdevrefinery.com/forums/user/4395-themaster/">TheMaster</a><br>
            Original Concept by <a href="http://webdevrefinery.com/forums/user/3235-ianonavy/">ianonavy</a>
        </div>
    </div>
<script src="http://netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
</body>
</html>
