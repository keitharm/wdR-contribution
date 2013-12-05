<?php
require_once("functions.php");
# Days for stats
define("DAYS", 46);

$db = database();
$page = fixPage($_GET['page']);
if ($_GET['do'] == "search" && $_POST['user'] != null) {
    $count = $db->query("SELECT COUNT(*) FROM `total` WHERE `username` LIKE '%" . $_POST['user'] . "%'");
    $count = $count->fetchAll();
    $count = $count[0][0];

    if ($count == 1) {
        $result_text = "<quote>1 match found</quote>";
    } else {
        $result_text = "<quote>" . $count . " matches found</quote>";
    }
    $statement = $db->query("SELECT * FROM `total` WHERE `username` LIKE '%" . $_POST['user'] . "%' ORDER BY `rank` ASC LIMIT 25");
} else {
    $statement = $db->query("SELECT * FROM `total` ORDER BY `rank` ASC LIMIT $page, 25");
}
$statement->setFetchMode(PDO::FETCH_ASSOC);

$daysago = statsLastXDays("day", DAYS);
$posts = statsLastXDays("posts", DAYS);
$reps = statsLastXDays("reputation", DAYS);
$logins = statsLastXDays("loggedon", DAYS);
$points = statsLastXDays("points", DAYS);

?>
<!DOCTYPE html>
<html>
<head>
    <title>wdR Contribution</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css" type="text/css">
    <script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
    <script type="text/javascript">
        $(function () {
            $('#container').highcharts({
                title: {
                    text: 'wdR stats history - Last <?=DAYS?> days',
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
                    color: '#00FF00'
                }, {
                    name: 'Reputation Points',
                    data: <?=$reps?>,
                    color: '#FF0000'
                }, {
                    name: 'Logins',
                    data: <?=$logins?>,
                    color: '#0000FF'
                }, {
                    name: 'Points (normalized x/10)',
                    data: <?=$points?>,
                    color: '#FF00FF'
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
            <div class="col-md-8">
                <table class="table table-hover" id="rank">
                <form action='index.php?do=search' method='POST'>
                    <tr><td colspan='3' align='left'><?php if ($_GET['do'] != "search" || ($_GET['do'] == "search" && $_POST['user'] == null)) echo pageControls($_GET['page']) ?></td><td colspan='6' align='right'><?php echo $result_text ?>&nbsp;&nbsp;&nbsp;<input type='text' name='user' placeholder='Username' value='<?php echo $_POST["user"] ?>' autofocus>&nbsp;&nbsp;&nbsp;<input type='submit' value='Search'></td></tr>
                </form>
                    <?php
                        if ($_GET['do'] == "search" && $count > 25) {
                            echo "<tr><td colspan='9' align='center'><font color='red'>Your search returned over 25 results. Please be more specific.</font></td></tr>";
                        }
                    ?>
                    <tr>
                        <th>Rank</th>
                        <th>Change</th>
                        <th>Username</th>
                        <th>Score</th>
                        <th>Posts</th>
                        <th>Reputation</th>
                        <th>Activity</th>
                        <th>Signature</th>
                    </tr>
                    <?php
                        $users = 0;
                        while ($row = $statement->fetch()) {
                            echo "<tr>";
                            echo "<td align='center'>" . rankColor($row["rank"]) . "</td>";
                            echo "<td align='center'>" . getRankChange($row["userid"]) . "</td>";
                            echo "<td><img src='" . $row["avatar"] . "' width='25' height='25'>&nbsp;&nbsp;&nbsp;<a href='view.php?user=" . $row["username"] . "'>" . userColor($row["userid"], $row["username"]) . "</a></td>";
                            echo "<td align='center'>" . round($row["score"]) . " <font color='#28D308'>+" . round(getPointsChange($row["userid"], false)*$row["activity"]) . "</font></td>";
                            echo "<td align='center'>" . $row["posts"] . " " . getPostChange($row["userid"]) . "</td>";
                            echo "<td align='center'>" . $row["reputation"] . " " . getRepChange($row["userid"]) . "</td>";
                            echo "<td align='center'>" . round($row["activity"]*100, 2) . "%</td>";
                            echo "<td align='center'><a href='sig.php?theme=light&user=" . $row["username"] . "'><button type='button' class='btn btn-success'>Get Sig!</button></a></td>";
                            echo "</tr>";
                            $users++;
                        }
                        if ($users == 0) {
                            echo "<tr><td colspan='9' align='center'>User was not found</td></tr>";
                        }
                        if ($_GET['do'] == "search" && $count > 25) {
                            echo "<tr><td colspan='9' align='center'>. . . . .</td></tr>";
                        }
                    ?>
                </table>
            </div>
            <div class="col-md-4">
                <p>Users receive points via the following criteria: <br>
                    <ul>
                        <li>Post: 10 points.</li>
                        <li>Rep Point: 25 points.</li>
                        <li>Logged on in past 24 hours: 5 points.</li>
                    </ul>
                <p>Scores = points * activity.</p>
                <p>Users must have at least <b>5</b> posts in order to make it onto the leaderboards.</p>
                    Scores are calculated daily.<br>
                    Calculating stats since <?=date("F j, Y", START_TIME-7200)?><br><br><br>
                    Last calculated <b><?=getUpdateDate()?></b>.
                    <p class="text-muted">Full source available on <a href='https://github.com/solewolf/wdR-contribution/'>Github</a></p>
                </p>
            </div>
        </div>
        <div class="row footer">
            Created and Designed by <a href="http://webdevrefinery.com/forums/user/2338-sole-wolf/">Sole_Wolf</a> and <a href="http://webdevrefinery.com/forums/user/4395-themaster/">TheMaster</a><br>
            Original Concept by <a href="http://webdevrefinery.com/forums/user/3235-ianonavy/">ianonavy</a>
        </div>
    </div>
<script src="http://netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
</body>
</html>
