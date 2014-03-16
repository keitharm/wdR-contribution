<?php
// Make Apache set permissions for generated images rw for group
umask(0002);

chdir(dirname(__FILE__));

require("botfunctions.php");
require("../functions.php");

$chart_screen_command = 'xvfb-run --server-args="-screen 0, 1280x1024x24" ./wkhtmltoimage-amd64 --use-xserver --javascript-delay 3000 --quality 75 http://www.solewolf.com/wdr/bot/chart.php chart.png';

$leaderboard_screen_command = 'xvfb-run --server-args="-screen 0, 1280x1024x24" ./wkhtmltoimage-amd64 --use-xserver --javascript-delay 3000 --quality 75 http://www.solewolf.com/wdr/bot/leaderboards.php leaderboards.png';

exec($chart_screen_command);
sleep(1);
exec($leaderboard_screen_command);

$chart_image_url = uploadImage("chart.png");
$leaderboards_image_url = uploadImage("leaderboards.png");

$monthyear = date("F Y", time()-86400);
$lastmonthyear = date("F Y", strtotime("first day of last month"));

try {

    $dbh = database();
    $date = date("m/d/Y");

    $current_cycle = getLastCycle();
    $days_in_month = cal_days_in_month(CAL_GREGORIAN, date("n", time()-86400), date("Y", time()-86400));
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

    $sth = $dbh->prepare("SELECT userid, username, SUM(posts) FROM history WHERE cycle > :diff GROUP BY username ORDER BY SUM(posts) desc LIMIT 10");
    $sth->bindParam(":diff", $diff, PDO::PARAM_INT);
    $sth->execute();
    $sth->setFetchMode(PDO::FETCH_ASSOC);
    $top_posters = $sth->fetchAll();

    $sth = $dbh->prepare("SELECT userid, username, SUM(reputation) FROM history WHERE cycle > :diff GROUP BY username ORDER BY SUM(reputation) desc LIMIT 10");
    $sth->bindParam(":diff", $diff, PDO::PARAM_INT);
    $sth->execute();
    $sth->setFetchMode(PDO::FETCH_ASSOC);
    $top_reppers = $sth->fetchAll();

    $sth = $dbh->prepare("SELECT url FROM monthly_reports WHERE date=?");
    $sth->execute(array($lastmonthyear));
    $sth->setFetchMode(PDO::FETCH_ASSOC);
    $last_post_url = $sth->fetch();


} catch(PDOException $e) {
    echo $e->getMessage();
}

$poster = array();
$repper = array();

for ($i = 0; $i<10; $i++) {

    $poster[$i] = userBBColor($top_posters[$i]['userid'], $top_posters[$i]['username']);

}

for ($i = 0; $i<10; $i++) {

    $repper[$i] = userBBColor($top_reppers[$i]['userid'], $top_reppers[$i]['username']);

}

$title = "wdR Contribution - Monthly Report " . $monthyear;

$post = <<<POST
[center][color=#0000ff][size=7]wdR Contribution - Monthly Report[/size][/color][/center]
[center][color=#ff0000][size=5]{$monthyear} [/size][/color][/center]

[center][size=4]Previous month's report: {$last_post_url['url']}[/size][/center]

[center][size=5]Total Posts: [color=#008000][size=6]$total_posts [/size][/color] Total Reputation: [color=#008000][size=6]$total_reputation [/size][/color] Total Logins: [color=#008000][size=6]$total_logins [/size][/color][/size][/center]

[center][img=$chart_image_url][/center]

[center][u][size=5]Top 10 Posters[/size][/u][/center]

[center][size=5]$poster[0] - {$top_posters[0]['SUM(posts)']}[/size][/center]
[center][size=5]$poster[1] - {$top_posters[1]['SUM(posts)']}[/size][/center]
[center][size=5]$poster[2] - {$top_posters[2]['SUM(posts)']}[/size][/center]
[center][size=5]$poster[3] - {$top_posters[3]['SUM(posts)']}[/size][/center]
[center][size=5]$poster[4] - {$top_posters[4]['SUM(posts)']}[/size][/center]
[center][size=5]$poster[5] - {$top_posters[5]['SUM(posts)']}[/size][/center]
[center][size=5]$poster[6] - {$top_posters[6]['SUM(posts)']}[/size][/center]
[center][size=5]$poster[7] - {$top_posters[7]['SUM(posts)']}[/size][/center]
[center][size=5]$poster[8] - {$top_posters[8]['SUM(posts)']}[/size][/center]
[center][size=5]$poster[9] - {$top_posters[9]['SUM(posts)']}[/size][/center]

[center][u][size=5]Top 10 Reputation Accumulators[/size][/u][/center]

[center][size=5]$repper[0] - {$top_reppers[0]['SUM(reputation)']}[/size][/center]
[center][size=5]$repper[1] - {$top_reppers[1]['SUM(reputation)']}[/size][/center]
[center][size=5]$repper[2] - {$top_reppers[2]['SUM(reputation)']}[/size][/center]
[center][size=5]$repper[3] - {$top_reppers[3]['SUM(reputation)']}[/size][/center]
[center][size=5]$repper[4] - {$top_reppers[4]['SUM(reputation)']}[/size][/center]
[center][size=5]$repper[5] - {$top_reppers[5]['SUM(reputation)']}[/size][/center]
[center][size=5]$repper[6] - {$top_reppers[6]['SUM(reputation)']}[/size][/center]
[center][size=5]$repper[7] - {$top_reppers[7]['SUM(reputation)']}[/size][/center]
[center][size=5]$repper[8] - {$top_reppers[8]['SUM(reputation)']}[/size][/center]
[center][size=5]$repper[9] - {$top_reppers[9]['SUM(reputation)']}[/size][/center]

[center][u][size=6]Current Leaderboard[/size][/u][/center]

[center][img=$leaderboards_image_url][/center]
POST;

$url = post(BOT_USER, BOT_PASS, $title, $post);

$sth = $dbh->prepare("INSERT INTO monthly_reports (date, url) VALUES (?, ?)");
$sth->execute(array($monthyear, $url));

?>
