<?php
    require_once("functions.php");
?>
<!DOCTYPE html>
<html>
<head>
    <title>wdR Contribution</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css" type="text/css">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="../../assets/js/html5shiv.js"></script>
    <script src="../../assets/js/respond.min.js"></script>
    <![endif]-->

    <?php
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
    ?>
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
                        <th>Points</th>
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
                            echo "<td><img src='" . $row["avatar"] . "' width='25' height='25'>&nbsp;&nbsp;&nbsp;<a href='http://webdevrefinery.com/forums/user/{$row["userid"]}-{$row["username"]}'>{$row["username"]}</a></td>";
                            echo "<td align='center'>{$row["score"]}</td>";
                            echo "<td align='center'>{$row["points"]}</td>";
                            echo "<td align='center'>{$row["posts"]}</td>";
                            echo "<td align='center'>{$row["reputation"]}</td>";
                            echo "<td align='center'>" . $row["activity"]*100 . "%</td>";
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
                    Calculating stats since <?=date("F j, Y", START_TIME)?><br><br><br>
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





<script src="http://code.jquery.com/jquery.js"></script>
<script src="http://netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
</body>
</html>

