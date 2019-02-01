<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>db update</title>
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


function UpdateEverything()
{
    global $pdo, $table;
    $queryStr = "SELECT id, long_url FROM " . $table . " WHERE build_class = '255' ORDER BY id DESC";

    echo "updating...<br>";
    foreach ($pdo->query($queryStr) as $row)
    {
        echo FillMissingData($row['id'], $row['long_url']) . "<br>";
    }
    echo "update finished";
}

function FillMissingData($id, $lCode)
{
    global $pdo, $table;
    echo "ID-" . $id;

    $pos = strpos($lCode, "?lb=");
    if ($pos < 0)
        return " || ERROR, no ?lb= found";
    $lCode = substr($lCode, $pos+4);

    $lCode = decompressFromBase64($lCode);
    if (strlen($lCode) == 0)
        return " || ERROR, decompression failure, length 0";

    $pos = strpos($lCode, "?lcl=");
    if ($pos < 0)
        return " || ERROR, no ?lcl= found";
    $lCode = substr($lCode, $pos+5);

    $classID = substr($lCode, 0, 1);
    if ($classID > 8)
        return " || ERROR, class " . $classID;
    echo " || CLASS-". $classID;

    $pos = strpos($lCode, "&lpa=");
    if ($pos < 0)
        return " || ERROR, no &lpa= found";
    $lCode = substr($lCode, $pos+5);

    $pathID = substr($lCode, 0, 1);
    if ($pathID > 2)
        return " || ERROR, path " . $pathID;
    echo " || PATH-". $pathID;

    $pos = strpos($lCode, "&lve=");
    if ($pos < 0)
        return " || ERROR, no &lve= found";
    $lCode = substr($lCode, $pos+5);

    $versionID = substr($lCode, 0, 1);
    if ($versionID > 5)
        return " || ERROR, version " . $versionID;
    echo " || VER-". $versionID;

    $pos = strpos($lCode, "&lp0=");
    if ($pos < 0)
        return " || ERROR, no &lp0= found";
    $lCode = substr($lCode, $pos+5);

    $path0 = substr($lCode, 0, strpos($lCode, "&"));
    echo " || <br>";
    $path0Arr = GetPathData($path0);

    $pos = strpos($lCode, "&lp1=");
    if ($pos < 0)
        return " || ERROR, no &lp1= found";
    $lCode = substr($lCode, $pos+5);

    $path1 = substr($lCode, 0, strpos($lCode, "&"));
    echo " || BLUE || <br>";
    $path1Arr = GetPathData($path1);

    $pos = strpos($lCode, "&lp2=");
    if ($pos < 0)
        return " || ERROR, no &lp2= found";
    $lCode = substr($lCode, $pos+5);

    $pos = strpos($lCode, "&");
    if (is_numeric($pos))
        $path2 = substr($lCode, 0, strpos($lCode, "&"));
    else
        $path2 = $lCode;
    echo " || RED || <br>";
    $path2Arr = GetPathData($path2);
    echo " || YELLOW || ";

    $query ="UPDATE " . $table . " SET build_class=?, build_path=?, build_ver=?, build_B=?, build_R=?, build_Y=?, build_spent=? WHERE id=?";
    $temp = $pdo->prepare($query);
    $totalSpent = 0;
    if ($pathID == 0)
        $totalSpent += end($path0Arr);
    else
        $totalSpent += end($path0Arr)*2;
    if ($pathID == 1)
        $totalSpent += end($path1Arr);
    else
        $totalSpent += end($path1Arr)*2;
    if ($pathID == 2)
        $totalSpent += end($path2Arr);
    else
        $totalSpent += end($path2Arr)*2;

    $temp->execute(array($classID, $pathID, $versionID, end($path0Arr), end($path1Arr), end($path2Arr), $totalSpent, $id));

}

function GetPathData($code)
{
    $arr = GetArrayOfIntsFromStr(decompressFromBase64($code));
    echo implode("|",$arr);
    return $arr;
}

function GetArrayOfIntsFromStr($str)
{
    $arr = array();
    $total = 0;
    if (strlen($str) != 28)
    {
        echo " ERROR length != 28 " . strlen($str);
        return $arr;
    }
    for ($i = 0; $i < strlen($str); $i++)
    {
        array_push($arr, $str[$i]);
        if (!is_numeric($arr[$i]))
            echo " NAN ";
        else
            $total += $arr[$i];
    }
    array_push($arr, $total);
    return $arr;
}






function decompressFromBase64($input) {
    if ($input == null) return "";
    $_keyStr = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
    $output = "";
    $ol = 0;
    $output_;
    $chr1;
    $chr2;
    $chr3;
    $enc1;
    $enc2;
    $enc3;
    $enc4;
    $i = 0;

    //$input = preg_replace('/[^A-Za-z0-9\+\/\=]/g', '', $input);

    while ($i < strlen($input)) {

        $enc1 = strpos($_keyStr, $input[$i++]);
        $enc2 = strpos($_keyStr, $input[$i++]);
        $enc3 = strpos($_keyStr, $input[$i++]);
        $enc4 = strpos($_keyStr, $input[$i++]);


        $chr1 = ($enc1 << 2) | ($enc2 >> 4);
        $chr2 = (($enc2 & 15) << 4) | ($enc3 >> 2);
        $chr3 = (($enc3 & 3) << 6) | $enc4;

        if ($ol%2==0) {
            $output_ = $chr1 << 8;

            if ($enc3 != 64) {
                $output .= fromCharCode(intval($output_ | $chr2));
            }
            if ($enc4 != 64) {
                $output_ = $chr3 << 8;
            }
        } else {
            $output .= fromCharCode(intval($output_ | $chr1));

            if ($enc3 != 64) {
                $output_ = $chr2 << 8;
            }
            if ($enc4 != 64) {
                $output .= fromCharCode(intval($output_ | $chr3));
            }
        }
        $ol+=3;
    }

    return decompress($output);
}

function fromCharCode($stuff)
{
    return mb_convert_encoding('&#' . $stuff . ';', 'UTF-8', 'HTML-ENTITIES');
}


//from http://eqcode.com/wiki/CharCodeAt
function charCodeAt($str, $num) { return utf8_ord(utf8_charAt($str, $num)); }
function utf8_ord($ch) {
    $len = strlen($ch);
    if($len <= 0) return false;
    $h = ord($ch{0});
    if ($h <= 0x7F) return $h;
    if ($h < 0xC2) return false;
    if ($h <= 0xDF && $len>1) return ($h & 0x1F) <<  6 | (ord($ch{1}) & 0x3F);
    if ($h <= 0xEF && $len>2) return ($h & 0x0F) << 12 | (ord($ch{1}) & 0x3F) << 6 | (ord($ch{2}) & 0x3F);
    if ($h <= 0xF4 && $len>3) return ($h & 0x0F) << 18 | (ord($ch{1}) & 0x3F) << 12 | (ord($ch{2}) & 0x3F) << 6 | (ord($ch{3}) & 0x3F);
    return false;
}
function utf8_charAt($str, $num) { return mb_substr($str, $num, 1, 'UTF-8'); }
//end



function readBit($data) {
    $res = $data->val & $data->position;
    $data->position >>= 1;
    if ($data->position == 0) {
        $data->position = 32768;
        $data->val = charCodeAt($data->string, $data->index++);
    }

    return $res>0 ? 1 : 0;
}

function readBits($numBits, $data) {
    $res = 0;
    $maxpower = pow(2,$numBits);
    $power=1;
    while ($power!=$maxpower) {
        $res |= readBit($data) * $power;
        $power <<= 1;
    }

    return $res;
}

function decompress($compressed) {
    $dictionary = array();
    $nextZ;
    $enlargeIn = 4;
    $dictSize = 4;
    $numBits = 3;
    $entry = "";
    $result = "";
    $i;
    $w;
    $c;
    $errorCount=0;
    $literal;
    $data = new stdClass;
    $data->string = $compressed;
    $data->val = charCodeAt($compressed, 0);

    $data->position = 32768;
    $data->index = 1;

    for ($i = 0; $i < 3; $i += 1) {
        $dictionary[$i] = $i;
    }

    $nextZ = readBits(2, $data);
    switch ($nextZ) {
        case 0:
            $c = fromCharCode(intval(readBits(8, $data)));
            break;
        case 1:
            $c = fromCharCode(intval(readBits(16, $data)));
            break;
        case 2:
            return "error 1";
    }
    $dictionary[3] = $c;
    $w = $result = $c;
    while (true) {
        $c = readBits($numBits, $data);

        switch ($c) {
            case 0:
                if ($errorCount++ > 10000) return "Error";
                $c = fromCharCode(intval(readBits(8, $data)));

                $dictionary[$dictSize++] = $c;
                $c = $dictSize-1;
                $enlargeIn--;
                break;
            case 1:
                $c = fromCharCode(intval(readBits(16, $data)));

                $dictionary[$dictSize++] = $c;
                $c = $dictSize-1;
                $enlargeIn--;
                break;
            case 2:
                return $result;
        }

        if ($enlargeIn == 0) {
            $enlargeIn = pow(2, $numBits);
            $numBits++;
        }

        if (isset($dictionary[$c])) {
            $entry = $dictionary[$c];
        }
        else {
            if ($c === $dictSize) {
                $entry = $w . $w[0];
            }
            else {
                echo "error 3 ";
            }
        }
        $result .= $entry;

        $dictionary[$dictSize++] = $w . $entry[0];
        $enlargeIn--;

        $w = $entry;

        if ($enlargeIn == 0) {
            $enlargeIn = pow(2, $numBits);
            $numBits++;
        }

    }
    return $result;
}

?>

</body>
</html>