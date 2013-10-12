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
        $statement = $db->query("SELECT * FROM users ORDER BY score desc LIMIT 100");
        $statement->setFetchMode(PDO::FETCH_ASSOC);
    ?>
</head>
<body>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>wdR Contribution</h1>
            </div>
            <div class="col-md-2">
            </div>
        </div>
        <div class="row">
            <div class="col-md-8">
                <table class="table table-hover">
                    <tr>
                        <th>Rank</th>
                        <th>Username</th>
                        <th>Reputation</th>
                        <th>Posts</th>
                        <th>Join Date</th>
                        <th>Score</th>
                        <th>Signature</th>
                    </tr>
                    <?php
                        $rank = 1;
                        while ($row = $statement->fetch()) {
                            echo "<tr>";
                            echo "<td>$rank</td>";
                            echo "<td>{$row["username"]}</td>";
                            echo "<td>{$row["reputation"]}</td>";
                            echo "<td>{$row["posts"]}</td>";
                            echo "<td>{$row["reputation"]}</td>";
                            echo "<td>{$row["score"]}</td>";
                            echo "<td><a href='sig.php?theme=light&user=" . $row["username"] . "'><button type='button' class='btn btn-success'>Get Sig!</button></a></td>";
                            echo "</tr>";
                            $rank++;
                        }
                    ?>
                </table>
            </div>
            <div class="col-md-4">
                <p>Score is calculated by: <br>reputation * (posts/minutes since joining)<br><br>Users are ranked by their score.</p>
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