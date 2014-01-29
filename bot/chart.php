<?php
require_once("../functions.php");
# Days for stats
$time = time() - 86400;
define("DAYS", cal_days_in_month(CAL_GREGORIAN, date("n", ($time)), date("Y", ($time))));

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
                    text: 'wdR stats for <?=date("F Y", $time)?>',
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
