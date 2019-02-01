<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.5"/>
    <meta name="description" content="Lord of the Rings Online trait planner and calculator. Updated for Helm's Deep expansion.">
    <meta content="yes" name="apple-mobile-web-app-capable">

    <meta name="robots" content="index,follow,noarchive">
    <title>LOTRO Trait Tree Planner</title>
    <link href="css/A.css" rel="stylesheet" media="screen" type="text/css">
    <link href="css/lotrottpM.css" rel="stylesheet" type="text/css" media="all">
    <script src="https://cdn.rawgit.com/schenkty/lotrostatus/master/points.js"></script>
    <script src="js/jquery-2.0.3.js"></script>
    <script src="js/lz-string-1.3.3.js"></script>
    <script src="js/lotrottpM.js"></script>
    <?php include('include/gainc2.php'); ?>
</head>
<body onload="LTTPInit();">
<div id="wrapperContainer">
<div id="pageContent">
    <table id="tableLayout">
        <tr>
            <td id="tdClass" class="tdTree">
            <div id="divClassButtons">
            <div id="content">
            <span class="graytitle">Classes</span>
            <ul class="pageitem">
            <li class="textbox"><span class="header">Choose a Class</span><p>
            Please choose a class to continue</p>
            </li>
            <li class="menu"><a href="#" onclick="PickClassButtonPressed(9);">
            <img alt="list" src="img/xbeor.png"><span class="name">The Beorning</span><span class="arrow"></span></a></li>
            <li class="menu"><a href="#" onclick="PickClassButtonPressed(0);">
            <img alt="list" src="img/xburg.png"><span class="name">The Burglar</span><span class="arrow"></span></a></li>
            <li class="menu"><a href="#" onclick="PickClassButtonPressed(1);">
            <img alt="list" src="img/xcap.png"><span class="name">The Captain</span><span class="arrow"></span></a></li>
            <li class="menu"><a href="#" onclick="PickClassButtonPressed(2);">
            <img alt="list" src="img/xchamp.png"><span class="name">The Champion</span><span class="arrow"></span></a></li>
            <li class="menu"><a href="#" onclick="PickClassButtonPressed(3);">
            <img alt="list" src="img/xguard.png"><span class="name">The Guardian</span><span class="arrow"></span></a></li>
            <li class="menu"><a href="#" onclick="PickClassButtonPressed(4);">
            <img alt="list" src="img/xhunt.png"><span class="name">The Hunter</span><span class="arrow"></span></a></li>
            <li class="menu"><a href="#" onclick="PickClassButtonPressed(5);">
            <img alt="list" src="img/xlore.png"><span class="name">The Lore-master</span><span class="arrow"></span></a></li>
            <li class="menu"><a href="#" onclick="PickClassButtonPressed(6);">
            <img alt="list" src="img/xmini.png"><span class="name">The Minstrel</span><span class="arrow"></span></a></li>
            <li class="menu"><a href="#" onclick="PickClassButtonPressed(7);">
            <img alt="list" src="img/xrune.png"><span class="name">The Rune-keeper</span><span class="arrow"></span></a></li>
            <li class="menu"><a href="#" onclick="PickClassButtonPressed(8);">
            <img alt="list" src="img/xward.png"><span class="name">The Warden</span><span class="arrow"></span></a></li>
            </ul></div>
            <hr>
            Load <u>Case Sensitive</u> Build Code<br>
            <textarea id="textAreaInput" class="textAreaLog" onclick="$(this).val('');" wrap="hard" spellcheck="false" rows="6" cols="24" style="resize:none"></textarea><br>
            <input type="button" onclick="LoadBuildFromCodePressed()" value="Load Build Code" style="width:150px"><br>
            <input type="button" onclick="ClearTAILog()" value="Clear Box" style="width:150px">

            </div>
        </tr>
        <tr>
            <td id="divChangeClassVersion" class="tdPath" style="display:none; padding-bottom: 5px">
                <div id="divChangeClassVersion">
                <div id="divVersionButtons"><select id="selectVersionScroll" style="max-width:100px"></select><input type='button' onclick='ChangeVersionPressed()' value='Change Version'></div>
                <div id="divVersionInfo"></div>
                </td>
            </td>
        </tr>
        <tr>
            <td id="tdPath0" class="tdPath" style="display:none">
            </td>
        </tr>
        <tr class="blackBG">
            <td id="tdTree0" class="tdTree" style="background-color: rgba(53, 83, 255, .25); display:none">
                <div class="divTreeG" id="divTreeG0" style="background-color: rgba(53, 83, 255, .5)"></div>
                <div class="divTree" id="divTreeA0"></div>
                <div class="divTreeT" id="divTreeT0" style="position: absolute"></div>
                <div class="divTree" id="divTree0"></div>
            </td>
        </tr>
        <tr class="blackBG">
            <td id="tdLow0" class="tdLow" style="background-color: rgba(53, 83, 255, .25); display:none">
                <div id="divLowPickPath0"></div>
                <div id="divLow0"></div>
            </td>
        </tr>
        <tr>
            <td class="tdTransBreak"></td>
        </tr>
        <tr>
            <td id="tdPath1" class="tdPath" style="display:none">
            </td>
        </tr>
        <tr class="blackBG">
            <td id="tdTree1" class="tdTree" style="background-color: rgba(255, 0, 0, .25); display:none">
                <div class="divTreeG" id="divTreeG1" style="background-color: rgba(255, 0, 0, .5)"></div>
                <div class="divTree" id="divTreeA1"></div>
                <div class="divTreeT" id="divTreeT1" style="position: absolute"></div>
                <div class="divTree" id="divTree1"></div>
            </td>
        </tr>
        <tr class="blackBG">
            <td id="tdLow1" class="tdLow" style="background-color: rgba(255, 0, 0, .25); display:none">
                <div id="divLowPickPath1"></div>
                <div id="divLow1"></div>
            </td>
        </tr>
        <tr>
            <td class="tdTransBreak"></td>
        </tr>
        <tr>
            <td id="tdPath2" class="tdPath" style="display:none">
            </td>
        </tr>
        <tr class="blackBG">
            <td id="tdTree2" class="tdTree" style="background-color: rgba(254, 255, 0, .25); display:none">
                <div class="divTreeG" id="divTreeG2" style="background-color: rgba(254, 255, 0, .5)"></div>
                <div class="divTree" id="divTreeA2"></div>
                <div class="divTreeT" id="divTreeT2" style="position: absolute"></div>
                <div class="divTree" id="divTree2"></div>
            </td>
        </tr>
        <tr class="blackBG">
            <td id="tdLow2" class="tdLow" style="background-color: rgba(254, 255, 0, .25); display:none">
                <div id="divLowPickPath2"></div>
                <div id="divLow2"></div>
            </td>
        </tr>
        <tr>
        <td id="tdBonus" style="display:none">
            <div class="divBonusG" id="divTreeG3" style="background-color: rgba(255, 165, 0, .5); display:none"></div>
            <div id="divBonus" style="position: absolute; display:none"></div>
        </td>
        </tr>
        <tr>
        <td id="tdLowest" style="background-color: rgba(0, 0, 0, 0); display:none">
             <div style="min-height:270px; max-height: 270px; text-align:center">
             <input type='button' onclick='GoToSelectClass()' value='Choose Another Class'>
             <hr>
             <textarea id="textAreaLog" class="textAreaLog" wrap="hard" spellcheck="false" readonly="true" rows="9" cols="27" style="resize:none"></textarea><br>
             <input type="button" onclick="CreateBuildURLM()" value="Generate Build Code" style="width:150px"><br>
             <input type="button" onclick="ClearTALog()" value="Clear Box" style="width:150px">

             </div>
        </td>
        </tr>
        <tr>
        <td>
        <div id="customFooter" style="text-align: center; min-height: 130px; max-width:250px"><span class="ttF15" style="font-size: 10px">Copyright &copy; 2013-2015<br>All content from <a href="http://www.lotro.com" class="lotrottpLowLink">Lord of the Rings Online&#8482;</a> is the property of <a href="http://www.lotro.com" class="lotrottpLowLink">Turbine&#8482;</a>.<br><a href="privacy.php" class="lotrottpLowLink">Privacy</a></span></div>

        </td>
        </tr>



    </table>







<div id="mStaticHoverFooter" style="display:none">
<div id="mFooterBlackBG" style="">
<div id="pointsAvailable" style="">Points Available: 65</div>
<div id="bonusTraitsHorContainer" style="">

<div id="bonusTraitsHorBG" style=""></div>
<div id="bonusTraitsHorTraits" style=""></div>
</div>
</div>

</div>

<div id="traitHoverButtonMain" style="position:absolute; display:none">
<div id="hBMain" class="traitHB" onclick='TMBTTPress(0);'></div>
</div>

<div id="traitHoverButtonMainF" style="position:fixed; display:none">
<div id="hBMainF" class="traitHB" onclick='TMBTTPress(0);'></div>
</div>


<div id="mStaticGreyScreen" style="display:none"></div>

<div id="invDiv" style="position: absolute; visibility: hidden"></div>
<div class="toolTip" id="toolTipOther" style="display:none"></div>

<div class="toolTip" id="toolTip1" style="display:none">
<div id="toolTipInner" style="padding-top:16px"></div>
<div id="toolTipBarrier" style="position:absolute; min-width:100%; max-width:100%; min-height:100%; max-height:100%; background-color: rgba(0, 0, 0, 0); top: 0; left: 0"></div>

<div id="traitHoverButtons" style="position:absolute; z-index: 11; top: -2px; left: -1px">
<div id="hBPlus" class="traitHB" onclick='TMBPlusPress();'></div>
<div id="hBMinus" class="traitHB" onclick='TMBMinusPress();' style="left:35px"></div>
<div id="hBTT0" class="traitHB" onclick='TMBTTPress(0);' style="left:80px"></div>
<div id="hBTT1" class="traitHB" onclick='TMBTTPress(1);' style="left:115px"></div>
<div id="hBTT2" class="traitHB" onclick='TMBTTPress(2);' style="left:150px"></div>
<div id="hBTT3" class="traitHB" onclick='TMBTTPress(3);' style="left:80px; top:-24px"></div>
<div id="hBTT4" class="traitHB" onclick='TMBTTPress(4);' style="left:115px; top:-24px"></div>
<div id="hBTT5" class="traitHB" onclick='TMBTTPress(5);' style="left:150px; top:-24px"></div>
<div id="ttCloseButton" class="ttClose" onclick='TTClosePress();' style="left:209px"></div>
</div>


</div>

</div>

<?php
    if (isset($_GET["c"])){
        $sCode = $_GET["c"];}
    else{
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
        $uB2 = -1;}
?>

<script type="text/javascript">
    var sCode = '<?php echo str_replace(' ','+',$sCode); ?>';
    var uBuild = ['<?php echo str_replace(' ','+',$uBC); ?>', '<?php echo str_replace(' ','+',$uBP); ?>', '<?php echo str_replace(' ','+',$uBV); ?>', '<?php echo str_replace(' ','+',$uB0); ?>', '<?php echo str_replace(' ','+',$uB1); ?>', '<?php echo str_replace(' ','+',$uB2); ?>'];
</script>

</body>
</html>