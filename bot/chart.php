<?php
require_once("../functions.php");
# Days for stats
define("DAYS", 30);

if (!isset($_GET['do'])) {
    $_GET['do'] = "";
}

if (!isset($_GET['page'])) {
    $_GET['page'] = 0;
}

if (!isset($_POST['user'])) {
    $_POST['user'] = "";
}

if (!isset($result_text)) {
    $result_text = "";
}

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
<html>
<head>
    <title>wdR Contribution</title>
    <script type="text/javascript" src="../js/jquery-1.10.2.min.js"></script>
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
                },
                chart: {
                    width: 1000
                }
            });
        });
    </script>
</head>
<body>
<script src="../js/highcharts.js"></script>
<div id="container" style="width: 100%; height: 250px; margin: 0 auto"></div>
</body>
</html>
