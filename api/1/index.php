<?php

require_once("../../functions.php");

$dbh = database();

/* Checks and validates type variable. MUST be set */
if ((isset($_POST['type'])) AND ($_POST['type']!="")) {

    $type = $_POST['type'];

    $valid = array("top", "all", "user");

    if (!in_array($type, $valid)) {
        echo $type;
        $error['error'] = "Invalid type.";
        echo json_encode($error);
        exit();
    }

} else {
    $error['error'] = "You must set a type!";
    echo json_encode($error);
    exit();
}

/* Checks for user variable. Only enforced if type=user */
if ((isset($_POST['user'])) AND ($_POST['user']!="")) {

    $user = $_POST['user'];

} else {
    if ($type=="user") {
        $error['error'] = "A user must be set for a single return type.";
        echo json_encode($error);
        exit();
    }
}

/* Checks and validates sort variable. Only enforced if type is NOT user */
if ((isset($_POST['sort'])) AND ($_POST['sort']!="")) {

    $sort = $_POST['sort'];

    $valid = array("posts", "rep", "logins");

    if (!in_array($sort, $valid)) {
        echo $sort;
        $error['error'] = "Invalid sort type.";
        echo json_encode($error);
        exit();
    }

} else {
    if ($type!="user") {
        $error['error'] = "You must set a sort value.";
        echo json_encode($error);
        exit();
    }
}

/* Checks for num variable. Only enforced if type=top */
if ((isset($_POST['num'])) && ($_POST['num']!="")) {

    $num = $_POST['num'];
    $num = intval($num);

} else {
    if ($type=="top") {
        $error['error'] = "You must set a number of top users, for the return type top.";
        echo json_encode($error);
        exit();
    }
}

switch ($type) {

    case "user":

        $sth = $dbh->prepare("SELECT * FROM total WHERE username=?");
        $sth->execute(array($user));
        $sth->setFetchMode(PDO::FETCH_ASSOC);
        $return = $sth->fetchAll();

        echo json_encode($return);
        exit();
        break;

    case "all":

        $sth = $dbh->prepare("SELECT * FROM total ORDER BY ?");
        $sth->execute(array($sort));
        $sth->setFetchMode(PDO::FETCH_ASSOC);
        $return = $sth->fetchAll();

        echo json_encode($return);
        exit();
        break;

    case "top":

        switch ($sort) {

            case "posts":

                $sth = $dbh->prepare("SELECT * FROM total ORDER BY posts desc LIMIT :limit");
                $sth->bindParam(':limit', $num, PDO::PARAM_INT);
                break;

            case "rep":

                $sth = $dbh->prepare("SELECT * FROM total ORDER BY reputation desc LIMIT :limit");
                $sth->bindParam(':limit', $num, PDO::PARAM_INT);
                break;

            case "logins":

                $sth = $dbh->prepare("SELECT * FROM total ORDER BY logins desc LIMIT :limit");
                $sth->bindParam(':limit', $num, PDO::PARAM_INT);
                break;

        }

        $sth->execute();
        $sth->setFetchMode(PDO::FETCH_ASSOC);
        $return = $sth->fetchAll();

        echo json_encode($return);
        exit();
        break;

}

?>