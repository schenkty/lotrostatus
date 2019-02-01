<?php
class ShortUrl {
    protected static $chars = "123456789bcdfghjkmnpqrstvwxyzBCDFGHJKLMNPQRSTVWXYZ";
    protected static $table = "short_urls";
    protected static $checkUrlExists = true;

    protected $pdo;
    protected $timestamp;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
        $this->timestamp = $_SERVER["REQUEST_TIME"];
    }

    public function urlToShortCode($url) {
        if (empty($url)) {
            throw new Exception("No URL was supplied.");
        }

        if ($this->validateUrlFormat($url) == false) {
            throw new Exception(
                "URL does not have a valid format.");
        }

        if (self::$checkUrlExists) {
            if (!$this->verifyUrlExists($url)) {
                throw new Exception(
                    "URL does not appear to exist.");
            }
        }

        $url = substr($url, strpos($url, "?lb="));

        $shortCode = $this->urlExistsInDb($url);
        if ($shortCode == false) {
            $shortCode = $this->createShortCode($url);

            echo $this->UpdateBuildFields($url);
        }

        return "<div><div id='divCode'> " . $shortCode . "</div></div>";
    }



    public function UpdateBuildFields($lCode)
    {
        $lCodeFull = $lCode;

        $pos = strpos($lCode, "?lb=");
        if ($pos < 0)
            return " || ERROR, no ?lb= found";
        $lCode = substr($lCode, $pos+4);

        $lCode = $this->decompressFromBase64($lCode);
        if (strlen($lCode) == 0)
            return " || ERROR, decompression failure, length 0";

        $pos = strpos($lCode, "?lcl=");
        if ($pos < 0)
            return " || ERROR, no ?lcl= found";
        $lCode = substr($lCode, $pos+5);

        $classID = substr($lCode, 0, 1);
        if ($classID > 9)
            return " || ERROR, class " . $classID;

        $pos = strpos($lCode, "&lpa=");
        if ($pos < 0)
            return " || ERROR, no &lpa= found";
        $lCode = substr($lCode, $pos+5);

        $pathID = substr($lCode, 0, 1);
        if ($pathID > 2)
            return " || ERROR, path " . $pathID;

        $pos = strpos($lCode, "&lve=");
        if ($pos < 0)
            return " || ERROR, no &lve= found";
        $lCode = substr($lCode, $pos+5);

        $versionID = substr($lCode, 0, 1);
        if ($versionID > 7)
            return " || ERROR, version " . $versionID;

        $pos = strpos($lCode, "&lp0=");
        if ($pos < 0)
            return " || ERROR, no &lp0= found";
        $lCode = substr($lCode, $pos+5);

        $path0 = substr($lCode, 0, strpos($lCode, "&"));
        $path0Arr = $this->GetPathData($path0);

        $pos = strpos($lCode, "&lp1=");
        if ($pos < 0)
            return " || ERROR, no &lp1= found";
        $lCode = substr($lCode, $pos+5);

        $path1 = substr($lCode, 0, strpos($lCode, "&"));
        $path1Arr = $this->GetPathData($path1);

        $pos = strpos($lCode, "&lp2=");
        if ($pos < 0)
            return " || ERROR, no &lp2= found";
        $lCode = substr($lCode, $pos+5);

        $pos = strpos($lCode, "&");
        if (is_numeric($pos))
            $path2 = substr($lCode, 0, strpos($lCode, "&"));
        else
            $path2 = $lCode;

        $path2Arr = $this->GetPathData($path2);

        $query ="UPDATE " . self::$table . " SET build_class=?, build_path=?, build_ver=?, build_B=?, build_R=?, build_Y=?, build_spent=? WHERE long_url=? LIMIT 1";
        $temp = $this->pdo->prepare($query);
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

        $temp->execute(array($classID, $pathID, $versionID, end($path0Arr), end($path1Arr), end($path2Arr), $totalSpent, $lCodeFull));



    }

    public function GetPathData($code)
    {
        $arr = $this->GetArrayOfIntsFromStr($this->decompressFromBase64($code));
        //echo implode("|",$arr);
        return $arr;
    }

    public function GetArrayOfIntsFromStr($str)
    {
        $arr = array();
        $total = 0;
        if (strlen($str) != 28)
        {
            //echo " ERROR length != 28 " . strlen($str);
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






    public function decompressFromBase64($input) {
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
                    $output .= $this->fromCharCode(intval($output_ | $chr2));
                }
                if ($enc4 != 64) {
                    $output_ = $chr3 << 8;
                }
            } else {
                $output .= $this->fromCharCode(intval($output_ | $chr1));

                if ($enc3 != 64) {
                    $output_ = $chr2 << 8;
                }
                if ($enc4 != 64) {
                    $output .= $this->fromCharCode(intval($output_ | $chr3));
                }
            }
            $ol+=3;
        }

        return $this->decompress($output);
    }

    public function fromCharCode($stuff)
    {
        return mb_convert_encoding('&#' . $stuff . ';', 'UTF-8', 'HTML-ENTITIES');
    }


    //from http://eqcode.com/wiki/CharCodeAt
    public function charCodeAt($str, $num) { return $this->utf8_ord($this->utf8_charAt($str, $num)); }
    public function utf8_ord($ch) {
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
    public function utf8_charAt($str, $num) { return mb_substr($str, $num, 1, 'UTF-8'); }
    //end



    public function readBit($data) {
        $res = $data->val & $data->position;
        $data->position >>= 1;
        if ($data->position == 0) {
            $data->position = 32768;
            $data->val = $this->charCodeAt($data->string, $data->index++);
        }

        return $res>0 ? 1 : 0;
    }

    public function readBits($numBits, $data) {
        $res = 0;
        $maxpower = pow(2,$numBits);
        $power=1;
        while ($power!=$maxpower) {
            $res |= $this->readBit($data) * $power;
            $power <<= 1;
        }

        return $res;
    }

    public function decompress($compressed) {
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
        $data->val = $this->charCodeAt($compressed, 0);

        $data->position = 32768;
        $data->index = 1;

        for ($i = 0; $i < 3; $i += 1) {
            $dictionary[$i] = $i;
        }

        $nextZ = $this->readBits(2, $data);
        switch ($nextZ) {
            case 0:
                $c = $this->fromCharCode(intval($this->readBits(8, $data)));
                break;
            case 1:
                $c = $this->fromCharCode(intval($this->readBits(16, $data)));
                break;
            case 2:
                return "error 1";
        }
        $dictionary[3] = $c;
        $w = $result = $c;
        while (true) {
            $c = $this->readBits($numBits, $data);

            switch ($c) {
                case 0:
                    if ($errorCount++ > 10000) return "Error";
                    $c = $this->fromCharCode(intval($this->readBits(8, $data)));

                    $dictionary[$dictSize++] = $c;
                    $c = $dictSize-1;
                    $enlargeIn--;
                    break;
                case 1:
                    $c = $this->fromCharCode(intval($this->readBits(16, $data)));

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





    protected function validateUrlFormat($url) {
        return filter_var($url, FILTER_VALIDATE_URL,
            FILTER_FLAG_HOST_REQUIRED);
    }

    protected function verifyUrlExists($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch,  CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        $response = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return (!empty($response) && $response != 404);
    }

    protected function urlExistsInDb($url) {
        $query = "SELECT short_code FROM " . self::$table .
            " WHERE long_url = :long_url LIMIT 1";
        $stmt = $this->pdo->prepare($query);
        $params = array(
            "long_url" => $url
        );
        $stmt->execute($params);

        $result = $stmt->fetch();
        return (empty($result)) ? false : $result["short_code"];
    }

    protected function createShortCode($url) {
        $id = $this->insertUrlInDb($url);
        $shortCode = $this->convertIntToShortCode($id);
        $this->insertShortCodeInDb($id, $shortCode);
        return $shortCode;
    }

    protected function insertUrlInDb($url) {
        $query = "INSERT INTO " . self::$table .
            " (long_url, date_created) " .
            " VALUES (:long_url, :timestamp)";
        $stmnt = $this->pdo->prepare($query);
        $params = array(
            "long_url" => $url,
            "timestamp" => $this->timestamp
        );
        $stmnt->execute($params);

        return $this->pdo->lastInsertId();
    }

    protected function convertIntToShortCode($id) {
        $id = intval($id);
        if ($id < 1) {
            throw new Exception(
                "The ID is not a valid integer");
        }

        $length = strlen(self::$chars);
        // make sure length of available characters is at
        // least a reasonable minimum - there should be at
        // least 10 characters
        if ($length < 10) {
            throw new Exception("Length of chars is too small");
        }

        $code = "";
        while ($id > $length - 1) {
            // determine the value of the next higher character
            // in the short code should be and prepend
            $code = self::$chars[fmod($id, $length)] .
                $code;
            // reset $id to remaining value to be converted
            $id = floor($id / $length);
        }

        // remaining value of $id is less than the length of
        // self::$chars
        $code = self::$chars[$id] . $code;

        return $code;
    }

    protected function insertShortCodeInDb($id, $code) {
        if ($id == null || $code == null) {
            throw new Exception("Input parameter(s) invalid.");
        }
        $query = "UPDATE " . self::$table .
            " SET short_code = :short_code WHERE id = :id";
        $stmnt = $this->pdo->prepare($query);
        $params = array(
            "short_code" => $code,
            "id" => $id
        );
        $stmnt->execute($params);

        if ($stmnt->rowCount() < 1) {
            throw new Exception(
                "Row was not updated with short code.");
        }

        return true;
    }

    public function shortCodeToUrl($code, $increment = false) {
        if (empty($code)) {
            throw new Exception("No short code was supplied.");
        }

        if ($this->validateShortCode($code) == false) {
            throw new Exception(
                "Short code does not have a valid format.");
        }

        $urlRow = $this->getUrlFromDb($code);
        if (empty($urlRow)) {
            throw new Exception(
                "Short code does not appear to exist.");
        }

        if ($increment == true) {
            $this->incrementCounter($urlRow["id"]);
        }

        return $urlRow["long_url"];
    }

    protected function validateShortCode($code) {
        return preg_match("|[" . self::$chars . "]+|", $code);
    }

    protected function getUrlFromDb($code) {
        $query = "SELECT id, long_url FROM " . self::$table .
            " WHERE short_code = :short_code LIMIT 1";
        $stmt = $this->pdo->prepare($query);
        $params=array(
            "short_code" => $code
        );
        $stmt->execute($params);

        $result = $stmt->fetch();
        return (empty($result)) ? false : $result;
    }

    protected function incrementCounter($id) {
        $query = "UPDATE " . self::$table .
            " SET counter = counter + 1 WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $params = array(
            "id" => $id
        );
        $stmt->execute($params);
    }
}?>