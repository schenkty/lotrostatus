<?php
include "include/ShortUrl.php";
include "include/ShortUrlAccess.php";

//$code = $_GET["c"];
//$url;

try {
    //$url = $shortUrl->shortCodeToUrl($code);
    //header("Location: " . $url);
    //exit;
}


catch (Exception $e) {
    // log exception and then redirect to error page.
    header("Location: /error");
    exit;
}

?>


<?php echo $shortUrl->urlToShortCode(str_replace(' ','+',$_GET["c"])); ?>
