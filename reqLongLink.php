<?php
include "include/ShortUrl.php";
include "include/ShortUrlAccess.php";

$code = $_GET["c"];

try {
    $url = $shortUrl->shortCodeToUrl($code);
    echo "<div><div id='divCode'> " . $url . "</div></div>";
}


catch (Exception $e) {
    // log exception and then redirect to error page.
    header("Location: /error");
    exit;
}?>

