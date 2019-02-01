<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>test</title>
</head>
<body>
<?php
$pdo;
$table = "short_urls";
include 'include/db-config.php';

try {
    $pdo = new PDO("mysql:dbname=$dbname;host=$servername",
        $username, $password);

}
catch (PDOException $e) {
    echo "Error: Failed to establish connection to database.";
    exit;
}


$queryStr = "SELECT * FROM " . $table . " ORDER BY id ASC";

$result = "";
$pdoPrep = $pdo->query($queryStr);

foreach ($pdoPrep as $row)
{
    $result .= "ID-" . $row['id'] . " || " . $row['counter'] . " || " . $row['long_url'] . " || " . $row['date_created'] .
        "<br>SHORT-" . $row['short_code'] .
        " || CLASS-" . $row['build_class'] .
        " || PATH-" . $row['build_path'] .
        " || VER-" . $row['build_ver'] .
        " || B-" . $row['build_B'] .
        " || R-" . $row['build_R'] .
        " || Y-" . $row['build_Y'] .
        " || S-" . $row['build_spent'] ."<br>";
}

echo $result;


?>
</body>
</html>