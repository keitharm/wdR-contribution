<?php
require_once("functions.php");

$db = database();
$statement = $db->query("SELECT * FROM `total` ORDER BY `rank` ASC LIMIT 10");
$statement->setFetchMode(PDO::FETCH_ASSOC);


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
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-16">
                <table class="table table-hover" id="rank">
                    <tr>
                        <th>Rank</th>
                        <th>Username</th>
                        <th>Score</th>
                        <th>Posts</th>
                        <th>Reputation</th>
                        <th>Activity</th>
                    </tr>
                    <?php
                        $users = 0;

                        while ($row = $statement->fetch()) {
                            if ($row["avatar"] == "images/wdr_default.png") {
                                $row["avatar"] = "../images/wdr_default.png";
                            }
                            echo "<tr>";
                            echo "<td align='center'>" . rankColor($row["rank"]) . "</td>";
                            echo "<td><img src='" . $row["avatar"] . "' width='25' height='25'>&nbsp;&nbsp;&nbsp;<a href='view.php?user=" . $row["username"] . "'>" . userColor($row["userid"], $row["username"]) . "</a></td>";
                            echo "<td align='center'>" . round($row["score"]) . "</font></td>";
                            echo "<td align='center'>" . $row["posts"] . "</td>";
                            echo "<td align='center'>" . $row["reputation"] . "</td>";
                            echo "<td align='center'>" . round($row["activity"]*100, 2) . "%</td>";
                            echo "</tr>";
                            $users++;
                        }
                    ?>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
