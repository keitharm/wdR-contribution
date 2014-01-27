<?php

chdir(dirname(__FILE__));

require("botfunctions.php");
require("../functions.php");

try {

    $dbh = database();
    $date = date("m/d/Y");

    $sth = $dbh->query("SELECT cycle FROM history ORDER BY cycle desc LIMIT 1");
    $sth->execute();
    $sth->setFetchMode(PDO::FETCH_ASSOC);
    $row = $sth->fetch();

    $current_cycle = $row['cycle'];
    $days_in_month = cal_days_in_month(CAL_GREGORIAN, date("m"), date("Y"));
    $diff = $current_cycle - $days_in_month;

    $sth = $dbh->prepare("SELECT SUM(posts) FROM history WHERE cycle > :diff");
    $sth->bindParam(":diff", $diff, PDO::PARAM_INT);
    $sth->execute();
    $sth->setFetchMode(PDO::FETCH_ASSOC);
    $row = $sth->fetch();

    $total_posts = $row['SUM(posts)'];

    $sth = $dbh->prepare("SELECT SUM(reputation) FROM history WHERE cycle > :diff");
    $sth->bindParam(":diff", $diff, PDO::PARAM_INT);
    $sth->execute();
    $sth->setFetchMode(PDO::FETCH_ASSOC);
    $row = $sth->fetch();

    $total_reputation = $row['SUM(reputation)'];

    $sth = $dbh->prepare("SELECT SUM(loggedon) FROM history WHERE cycle > :diff");
    $sth->bindParam(":diff", $diff, PDO::PARAM_INT);
    $sth->execute();
    $sth->setFetchMode(PDO::FETCH_ASSOC);
    $row = $sth->fetch();

    $total_logins = $row['SUM(loggedon)'];

    $sth = $dbh->prepare("SELECT userid, username, SUM(posts) FROM history WHERE cycle > :diff GROUP BY username ORDER BY SUM(posts) desc LIMIT 5");
    $sth->bindParam(":diff", $diff, PDO::PARAM_INT);
    $sth->execute();
    $sth->setFetchMode(PDO::FETCH_ASSOC);
    $top_posters = $sth->fetchAll();

    $sth = $dbh->prepare("SELECT userid, username, SUM(reputation) FROM history WHERE cycle > :diff GROUP BY username ORDER BY SUM(reputation) desc LIMIT 5");
    $sth->bindParam(":diff", $diff, PDO::PARAM_INT);
    $sth->execute();
    $sth->setFetchMode(PDO::FETCH_ASSOC);
    $top_reppers = $sth->fetchAll();


} catch(PDOException $e) {
    echo $e->getMessage();
}

$poster = array();
$repper = array();

for ($i = 0; $i<5; $i++) {

    $poster[$i] = userBBColor($top_posters[$i]['userid'], $top_posters[$i]['username']);

}

for ($i = 0; $i<5; $i++) {

    $repper[$i] = userBBColor($top_reppers[$i]['userid'], $top_reppers[$i]['username']);

}

$title = "wdR Contribution - Monthly Report " . $date;

$post = <<<POST
[center][color=#0000ff][size=7]wdR Contribution - Monthly Report[/size][/color][/center]
[center][color=#ff0000][size=5]$date [/size][/color][/center]
[center] [/center]
[center][size=5]Total Posts: [color=#008000][size=6]$total_posts [/size][/color] Total Rep.:
[color=#008000][size=6]$total_reputation [/size][/color] Total Logins: [color=#008000][size=6]$total_logins [/size]
[/color][/size][/center]

[center][img=http://solewolf.com/wdr/bot/chart.png][/center]
 
[center][u][size=5]Top 5 Posters[/size][/u][/center]
[center] [/center]
[center][size=5][color=#ff0000]$poster[0] - {$top_posters[0]['SUM(posts)']}[/color][color=#008000]20[/color][/size][/center]
[center][size=5][color=#ff0000]$poster[1] - {$top_posters[1]['SUM(posts)']}[/color][color=#008000]20[/color][/size][/center]
[center][size=5][color=#ff0000]$poster[2] - {$top_posters[2]['SUM(posts)']}[/color][color=#008000]20[/color][/size][/center]
[center][size=5][color=#ff0000]$poster[3] - {$top_posters[3]['SUM(posts)']}[/color][color=#008000]20[/color][/size][/center]
[center][size=5][color=#ff0000]$poster[4] - {$top_posters[4]['SUM(posts)']}[/color][color=#008000]20[/color][/size][/center]
[center] [/center]
[center][u][size=5]Top 5 Reputation Accumulators[/size][/u][/center]
[center] [/center]
[center][size=5][color=#ff0000]$repper[0] - {$top_reppers[0]['SUM(reputation)']}[/color][color=#008000]20[/color][/size][/center]
[center][size=5][color=#ff0000]$repper[1] - {$top_reppers[1]['SUM(reputation)']}[/color][color=#008000]20[/color][/size][/center]
[center][size=5][color=#ff0000]$repper[2] - {$top_reppers[2]['SUM(reputation)']}[/color][color=#008000]20[/color][/size][/center]
[center][size=5][color=#ff0000]$repper[3] - {$top_reppers[3]['SUM(reputation)']}[/color][color=#008000]20[/color][/size][/center]
[center][size=5][color=#ff0000]$repper[4] - {$top_reppers[4]['SUM(reputation)']}[/color][color=#008000]20[/color][/size][/center]
[center] [/center]
[center] [/center]
[center][u][size=6]Current Leaderboard[/size][/u][/center]
 
[center][img=http://solewolf.com/wdr/bot/leaderboards.png][/center]
POST;

post($botuser, $botpass, $title, $post);

?>
