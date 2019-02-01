<?php
    if (isset($_GET["c"])){
        $sCode = $_GET["c"];
        $cookie_name = "c";
        $cookie_value = $sCode;
        setcookie($cookie_name, $cookie_value, time() + 10000, "/");
    } else{
        $sCode = "none";}
    if (isset($_GET["lcl"])){
        $uBC = $_GET["lcl"];}
    else{
        $uBC = -1;}
    if (isset($_GET["lpa"])){
        $uBP = $_GET["lpa"];}
    else{
        $uBP = -1;}
    if (isset($_GET["lve"])){
        $uBV = $_GET["lve"];}
    else{
        $uBV = -1;}
    if (isset($_GET["lp0"])){
        $uB0 = $_GET["lp0"];}
    else{
        $uB0 = -1;}
    if (isset($_GET["lp1"])){
        $uB1 = $_GET["lp1"];}
    else{
        $uB1 = -1;}
    if (isset($_GET["lp2"])){
        $uB2 = $_GET["lp2"];}
    else{
        $uB2 = -1;
    }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="description" content="Lord of the Rings Online trait planner and calculator. Updated for Helm's Deep expansion.">
    <meta name="robots" content="index,follow,noarchive">
    <title>LOTRO Trait Tree Planner</title>
    <link href="css/wrapper.css" rel="stylesheet" type="text/css" media="all">
    <link href="css/lotrottp.css" rel="stylesheet" type="text/css" media="all">
    <script src="js/points.js"></script>
    <script src="js/jquery-2.0.3.js"></script>
    <script src="js/lz-string-1.3.3.js"></script>
    <script src="js/lotrottp.js"></script>
    <script type="text/javascript">
        function codeReset() {
            document.getElementById("vote").innerHTML = "";
            c = "blank";
        }
    </script>
    <?php include('include/gainc2.php'); ?>
</head>
<body onload="LTTPInit();">
<div id="wrapperContainer">
<center><br><div style="height: 55px; width: 100%;"><a href='https://ko-fi.com/J3J05NKX' target='_blank'><img height='50' style='border:0px;height:50px;' src='https://az743702.vo.msecnd.net/cdn/kofi1.png?v=0' border='0' alt='Buy Me a Coffee at ko-fi.com' /></a></div></dir></center>
<div id="pageContent">
    <table id="tableLayout">
        <tr>
            <td rowspan="8" id="tdLeft">
            </td>
            <td colspan="4" id="tdDesc">
                <div id="divDescTable">
                <div class="divDescSide" id="divDescLeft"></div>
                <div id="divDescCenter">
                <div class="bigLink">
                <a href="#" id="mobileLink" class="linkRed">View MOBILE Version</a><br>
                </div>
                <div class="txtBasicW14" style="margin-top: 10px">
                <a href="https://lotrottp.lotrostatus.com"><b style="color:white;">LOTRO Trait Tree Planner</b></a><br>
                
                Due to scaling bonuses in game, some data is approximated or missing.<br>This is denoted by [?] by or in place of numbers.
                </div>
                <noscript>
                    <span class="ttF13"><br><b>Please enable javascript.<br>This page will not work without it.</b></span>
                </noscript>
                </div>
                <div class="divDescSide" id="divDescRight">
                <input type='button' onclick="window.open('builds.php')" value='Player Created Builds' style='margin-bottom: 2px; width: 145px'><br>
                <input type='button' onclick='ChangeSLOptVisibility()' value='Mobile Compatibility' style='width: 145px'></div>
                </div>
            </td>
            <td rowspan="8" id="tdRight">
            </td>
        </tr>
        <tr>
            <td colspan="4" id="tdShowHideButtons" style="text-align: right; margin:0px; padding-right: 12px; padding-left: 13px">

            <div style="text-align: left; float: left">
            <input type='text' id="shortCodeInput" onclick="$(this).val('');" value='case sensitive code' size="22" maxlength="22" style='margin:0'>
            <input type='button' onclick='LoadBuildFromCodePressed()' value='Load Build Code' style='margin:0'>
            <input type="button" onclick="CreateBuildURL()" value="Generate Build Link/Code" style="width:200px"></div>

            <div style="text-align: right; float: right"><div class="txtBasicW14"><b>Show / Hide</b>
            <input type='button' onclick='ChangeClassVisibility()' value='Class Selection' style='margin:0'></div></div>

            </td>
            </tr>
        <tr>
            <td colspan="4" id="tdClass">
            <div id="divClassButtons"></div>
            <div id="divChangeClassVersion" style="display:none">
            <div id="divVersionButtons"><input type='button' onclick='PickClassVisibility(true); codeReset();' value='Pick Another Class'><select id="selectVersionScroll"></select><input type='button' onclick='ChangeVersionPressed()' value='Change Version'></div>
            <!-- Upvote and Downvote -->
            <div id="vote"></div>
            <script type="text/javascript">
                var c =  "<?php $code = $_COOKIE['c']; if ($code == "") { $code = "blank"; } echo $code; ?>";
                var voteElement = document.getElementById("vote");
                console.log("Build Code: " + c);
                if (c != "blank") {
                    voteElement.innerHTML =  "<p style='color:yellow;'>Vote for this Build:</p><div><input type=\"button\" onclick=\"window.open('https://lotrottp.lotrostatus.com/include/build_update.php?act=up&id=" + c + "')\" value=\"UpVote\"><input type=\"button\" onclick=\"window.open('https://lotrottp.lotrostatus.com/include/build_update.php?act=down&id=" + c + "')\" value=\"Downvote\"></div>"
                }   
            </script>
            <!-- End Upvote and Downvote System -->
            <br>
            <div id="divVersionInfo"></div>
            </div>
            </td>
        </tr>
        <tr>
            <td colspan="4" id="tdSLOpt" style="text-align:center; display:none">
            <div style="max-width: 100%">
            <textarea id="textAreaLog" class="textAreaLog" readonly="true" wrap="hard" spellcheck="false" rows="6" cols="82" style="resize:none; width:95%"></textarea><br>
            </div>
            <input type="button" onclick="CreateBuildURL()" value="Generate Build Link/Code" style="width:200px">
            <input type="button" onclick="ClearTALog()" value="Clear Log" style="width:150px">
            <input type="button" onclick="ChangeSLOptVisibility()" value="Hide Log" style="width:150px">
            <hr>
            <div class="txtBasicW14"><input id="ttAutoHideCheckBox" type="checkbox" checked autocomplete="off" onclick="ChangeTTAutoHide()">- Automatic Tooltip Hiding.<br>Disable this when using low resolutions and tooltips get off screen. Click anywhere to make them disappear.<br>Also allows viewing tooltips on touchscreens when disabled.</div>
            <hr>
            <div class="txtBasicW14"><input id="removeRankCheckBox" type="checkbox" autocomplete="off" onclick="ChangePressReduceRank()">- Pressing Reduces Trait Rank.<br>Enable to reduce ranks while pressing trait buttons.<br>This is handy for touchscreens.</div>
            <hr>
            </td>
        </tr>
        <tr>
            <td id="tdPathDummy">
            </td>
            <td id="tdPath0" class="tdPath">
            </td>
            <td id="tdPath1" class="tdPath">
            </td>
            <td id="tdPath2" class="tdPath">
            </td>
        </tr>
        <tr>
            <td id="tdBonus">
                <div class="divBonusG" id="divTreeG3" style="background-color: rgba(255, 165, 0, .5)"></div>
                <div id="divBonus" style="position: absolute"></div>
            </td>
            <td id="tdTree0" class="tdTree" style="background-color: rgba(53, 83, 255, .25)">
                <div class="divTreeG" id="divTreeG0" style="background-color: rgba(53, 83, 255, .5)"></div>
                <div class="divTree" id="divTreeA0"></div>
                <div class="divTreeT" id="divTreeT0" style="position: absolute"></div>
                <div class="divTree" id="divTree0"></div>

            </td>
            <td id="tdTree1" class="tdTree" style="background-color: rgba(255, 0, 0, .25)">
                <div class="divTreeG" id="divTreeG1" style="background-color: rgba(255, 0, 0, .5)"></div>
                <div class="divTree" id="divTreeA1"></div>
                <div class="divTreeT" id="divTreeT1" style="position: absolute"></div>
                <div class="divTree" id="divTree1"></div>
            </td>
            <td id="tdTree2" class="tdTree" style="background-color: rgba(254, 255, 0, .25)">
                <div class="divTreeG" id="divTreeG2" style="background-color: rgba(254, 255, 0, .5)"></div>
                <div class="divTree" id="divTreeA2"></div>
                <div class="divTreeT" id="divTreeT2" style="position: absolute"></div>
                <div class="divTree" id="divTree2"></div>
            </td>
        </tr>
        <tr>
            <td id="tdLow3">
                <div id="divLow3" style='vertical-align:top; text-align:center'></div>
            </td>
            <td id="tdLow0" class="tdLow" style="background-color: rgba(53, 83, 255, .25)">
                <div id="divLowPickPath0"></div>
                <div id="divLow0"></div>
            </td>
            <td id="tdLow1" class="tdLow" style="background-color: rgba(255, 0, 0, .25)">
                <div id="divLowPickPath1"></div>
                <div id="divLow1"></div>
            </td>
            <td id="tdLow2" class="tdLow" style="background-color: rgba(254, 255, 0, .25)">
                <div id="divLowPickPath2"></div>
                <div id="divLow2"></div>
            </td>
        </tr>
        <tr>
            <td id="tdSizePush" colspan="4" style="vertical-align:top">
                <div id="customFooter"><span class="ttF15" style="font-size: 10px">Copyright &copy; 2013-<?php echo date("Y"); ?><br>All content from <a href="http://www.lotro.com" class="lotrottpLowLink">Lord of the Rings Online&#8482;</a> is the property of <a href="http://www.lotro.com" class="lotrottpLowLink">Standing Stone Games LLC</a>.<br><a href="privacy.php" class="lotrottpLowLink">Privacy</a></span><br>
                </div>
            </td>
        </tr>
    </table>
<div id="invDiv" style="position: absolute; visibility: hidden"></div>
<div class="toolTip" id="toolTipOther" style="display:none"></div>

</div>

</div>
<script type="text/javascript">
    var sCode = '<?php echo str_replace(' ','+',$sCode); ?>';
    var uBuild = ['<?php echo str_replace(' ','+',$uBC); ?>', '<?php echo str_replace(' ','+',$uBP); ?>', '<?php echo str_replace(' ','+',$uBV); ?>', '<?php echo str_replace(' ','+',$uB0); ?>', '<?php echo str_replace(' ','+',$uB1); ?>', '<?php echo str_replace(' ','+',$uB2); ?>'];
</script>
</body>
</html>