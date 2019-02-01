<?php
include 'db-config.php';
setcookie("c", "", time() - 90000, "/");
$id = $_GET['id'];
$act = $_GET['act'];

if ($id == "" || $id == "null") {
    echo "<script>window.close();</script>";
}

// Set Cookie to restrict multi
$cookie_name = $id;
$cookie_value = "true";
setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day

$count = '0';
$redirect = "https://lotrottp.lotrostatus.com/l.php?c=$id"; 

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "SELECT counter FROM `short_urls` WHERE `short_code` = '$id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $count = $row["counter"];
        echo "Count:" .$count;
        echo "<br>";
    }
} else {
	header('Location: ' . $redirect);
}

if ($act == "up") {
    echo "Cookie: $_COOKIE[$id]";
    echo "<br>";
    if ($_COOKIE[$id] != "true") {
        $count++;  
    }
} else {
    echo "Cookie: $_COOKIE[$id]";
    echo "<br>";
    if ($_COOKIE[$id] != "true") {
        if ($count != 0) {
		    $count--;
		}   
    }
}

$sql = "UPDATE `short_urls` SET `counter`= '$count' WHERE `short_code` = '$id'";

if (mysqli_query($conn, $sql)) {
    echo "Record updated successfully";
    echo "<br>";
	echo "Count:" .$count;
	echo "<br>";
    header('Location: ' . $redirect);
} else {
    echo "Error updating record: " . mysqli_error($conn);
    header('Location: ' . $redirect);
}

mysqli_close($conn);
if ($act == "up"){
    echo "<script>alert('Thanks, Upvote Set!'); window.close();</script>";
} else {
    echo "<script>alert('Thanks, Downvote Set!'); window.close();</script>";
}
?>