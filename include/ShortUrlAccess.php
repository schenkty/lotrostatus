<?php
include 'db-config.php';
try {
    $pdo = new PDO("mysql:dbname=$dbname;host=$servername",
        $username, $password);

}
catch (PDOException $e) {
    trigger_error("Error: Failed to establish connection to database.");
    exit;
}

$shortUrl = new ShortUrl($pdo);
?>