<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="description" content="List of Lord of the Rings Online trait builds.">
    <meta content="width=device-width; initial-scale=1.0; maximum-scale=2.0; user-scalable=1;" name="viewport" />
    <meta name="robots" content="index,follow,noarchive">
    <title>LOTRO Trait Tree Planner Builds</title>
    <script src="js/jquery-2.0.3.js"></script>
    <script src="js/lotrottpList.js"></script>
    <style type="text/css">
    #selectClassScroll { width:120px}
    body {background-color: #111111; font-family: Verdana, Arial, serif; font-size: 14px; font-weight: bold; color: #FFFFFF; text-align:center}
    table {margin:auto; background-color: #333333; border:1px; border-spacing:3px 7px}
    td.tdUp {min-width:56px}
    tr.bRowB, td.colB {background-color: rgba(53, 83, 255, .2)}
    tr.bRowR, td.colR  {background-color: rgba(255, 0, 0, .2)}
    tr.bRowY, td.colY  {background-color: rgba(254, 255, 0, .2)}
    tr.bRow:hover, td.tdUpHover:hover {background-color: hsl(0,0%,40%); cursor: pointer}
    a:link, a:visited, a:active, a:hover {color:#FFFFFF; text-decoration: none}
    </style>
  <script>
    window.onload = LTTPListInit()
    {
     LTTPListInit;
    }
  </script>
    <?php include('include/gainc2.php'); ?>
</head>
<body onload="LTTPListInit();">
<?php
include('include/db-config.php');
$pdo;
$table = "short_urls";

try {
    $pdo = new PDO("mysql:dbname=$dbname;host=$servername",
        $username, $password);

}
catch (PDOException $e) {
    echo "Error: Failed to establish connection to database.";
    exit;
}

//z
$queryStr = "SELECT counter, short_code, build_class, build_path, build_ver, build_B, build_R, build_Y FROM " . $table . " ORDER BY id ASC";

$result = '<script type="text/javascript">var bArray = [';
$pdoPrep = $pdo->query($queryStr);

$firstSkip = true;

foreach ($pdoPrep as $row) {
    if ($firstSkip == false) {
        $result .= ',';
    } else {
        $firstSkip = false;
    }

    $result .= '{counter:' . $row['counter'] . ',' .
        'bShort:"' . $row['short_code'] . '",' .
        'bClass:' . $row['build_class'] . ',' .
        'path:' . $row['build_path'] . ',' .
        'ver:' . $row['build_ver'] . ',' .
        'pB:' . $row['build_B'] . ',' .
        'pR:' . $row['build_R'] . ',' .
        'pY:' . $row['build_Y'] . '}';
}

$result .= '];</script>';

echo $result;
?>
</body>
</html>