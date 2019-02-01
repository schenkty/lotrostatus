/*
 Copyright (c) 2013-2015
 Coded by Adam Groth
 All content from Lord of the Rings Online is the property of Turbine. www.lotro.com
 versionsArray = "0.0.0 12.0.1 12.1 12.2 13.0 13.1 14.0 20.0".split(" "),
*/

var CELL_X_OFFSET = 60,
    CELL_Y_OFFSET = 60,
    TREE_TOP_OFFSET = 18,
    TREE_LEFT_OFFSET = 19,
    arrowHeight2Rows, arrowWidth2Cols, $window, ttRefsArray = [],
    $ttOtherDivRef, $invDiv, $divTreeGArray = [],
    $divLowPickPathArray = [],
    $divlowpointsNStuffArray = [],
    $textAreaLog, $shortCodeInput, $selectVersionScroll, traitsBought = [0, 0, 0, 0],
    traitsBoughtPerTier = [
        [0, 0, 0, 0, 0, 0, 0, 0],
        [0, 0, 0, 0, 0, 0, 0, 0],
        [0, 0, 0, 0, 0, 0, 0, 0]
    ],
    pointsFree = pointsCap,
    /*pointsCap = 83,*/
    selectedClass = -1,
    selectedPath = 0,
    b2 = "<br><br>",
    b1 = "<br>",
    classPickedOnce = !1,
    pathSelectScreenB = !0,
    lastTTTitleWidth =
    0,
    buildFromUrlLoading = !1,
    buildFromUrlCache, autoHideTT = !0,
    pressReducesRank = !1,
    bypassGlobalClickOnce = !1,
    tooltipsGoWhere = [],
    savedBuilds, classButtonOrder = [9, 0, 1, 2, 3, 4, 5, 6, 7, 8],
    classes = "Burglar Captain Champion Guardian Hunter Lore-Master Minstrel Rune-Keeper Warden Beorning".split(" "),
    classesEnabled = [1, 1, 1, 1, 1, 1, 1, 1, 1, 1],
    classesUpToVersion = [
        [2, 3, 4, 5, 6, 7],
        [2, 3, 4, 5, 6, 7],
        [2, 3, 4, 5, 6, 7],
        [2, 3, 4, 5, 6, 7],
        [2, 3, 4, 5, 6, 7],
        [2, 3, 4, 5, 6, 7],
        [2, 3, 4, 5, 6, 7],
        [2, 3, 4, 5, 6, 7],
        [2, 3, 4, 5, 6, 7],
        [7]
    ],
    selectedVersion, classDBCacheState = [],
    classDBCache = [],
    versionsArray = "0.0.0 12.0.1 12.1 12.2 13.0 13.1 14.0 20.0".split(" "),
    latestGameVersion = 7,
    cStr = "% of Main-hand + bonus Damage;Cost: [?] Power;Melee Skill;Ranged Skill;% of Ranged + bonus Damage;% of Ranged Damage;Cost: [?] Morale".split(";"),
    pathDataArray = [],
    traitDataArray = [],
    skillsOtherDataArray = [],
    traitStates = [
        [],
        [],
        []
    ];

function ConstructPaths() {
    ResetHighlight();
    for (var a, b, e, d, c, f = 0; f < pathDataArray.length; f++) {
        e = $("#divTree" + f);
        e.empty();
        $("#divTreeLines" + f).hide();
        $("#divTreeA" + f).empty();
        b = pathDataArray[f].n;
        0 == f ? $("#tdPath0").empty().html(RS(12, b)) : 1 == f ? $("#tdPath1").empty().html(RS(13, b)) : 2 == f && $("#tdPath2").empty().html(RS(14, b));
        a = pathDataArray[f];
        b = "<div class='pathHead'>" + RS(15, a.head) + "</div>";
        b += "<div class='pathMain' id='pathMain" + f + "'>" + RS(11, a.main);
        0 < a.skills.length && (b += b1 + RS(8, "Skills Earned:"));
        b += "</div>";
        e.html(b);
        c = $("#pathMain" + f);
        d = c.height() + c.position().top + 10;
        b = "";
        for (var g = 0; g < a.skills.length; g++) {
            b += ReturnInteractiveTraitButton(selectedClass, 6, a.skills[g], skillsOtherDataArray[a.skills[g]].spriteIndex, d, c.position().left, !1);
            var h = RS(9, skillsOtherDataArray[a.skills[g]].n);
            b += "<div class='pathSkillName' style='top:" + (GetTopOffsetForTitle(h, 155, 32) + d - 2) + "px'>" + h + "</div>";
            d += 45
        }
        b += "<div class='pathLow' style='top:" + d + "px'>" + a.tt1() + "</div>";
        b += "<div class='pathBonus'>";
        for (d = 0; d < a.bonuses.length; d++) b +=
            ReturnInteractiveTraitButton(selectedClass, f + 3, d, a.bonuses[d].spriteIndex, d * CELL_Y_OFFSET, 0, !1);
        b += "</div>";
        e.append(b)
    }
    e = $("#divBonus");
    e.empty()
}

function GetTopOffsetForTitle(a, b, e) {
    $invDiv.empty();
    $invDiv.css("max-width", b);
    $invDiv.html(a);
    $invDiv.find("span:first").css("display", "block").css("line-height", "0.9");
    a = Math.round(e / 2 - $invDiv.height() / 2) - 1;
    lastTTTitleWidth = 220;
    $invDiv.css("max-width", "");
    return a
}

function ReturnInteractiveTraitButton(a, b, e, d, c, f, g) {
    var h = "" + a + b + e;
    b = "(" + b + ", " + e + ',"#' + h + '")';
    h = "<div class='imgTrait' id='" + h + "' onmouseover='CreateTooltip" + b + "' onmouseout='RemoveTooltip(false)'";
    return h = (g ? h + (" onclick='TraitPressed" + b + "' oncontextmenu='TraitUnpressed" + b + "; return false;'") : h + ("onclick='TraitPressedMobileShowTT" + b + "'")) + (" style='top:" + c + "px; left:" + f + 'px; background-image: url("img/lttp' + a + "v" + classesUpToVersion[selectedClass][selectedVersion] + '.png"); background-position: ' +
        -32 * d + "px 0px'></div>")
}

function TraitPressedMobileShowTT(a, b, e) {
    autoHideTT || (CreateTooltip(a, b, e), bypassGlobalClickOnce = !0)
}

function ResetHighlight() {
    window.getSelection && window.getSelection().removeAllRanges()
}

function ConstructTrees() {
    ResetHighlight();
    for (var a, b, e, d, c = 0; 3 > c; c++) {
        e = "";
        $("#divTreeLines" + c).show();
        a = $("#divTree" + c);
        a.empty();
        d = traitDataArray[c];
        b = $("#divTreeA" + c);
        b.empty();
        for (var f = 0; f < d.length; f++)
            if ("" != d[f].n && (e += ReturnInteractiveTraitButton(selectedClass, c, f, d[f].spriteIndex, CELL_Y_OFFSET * Math.floor(f / 4) + TREE_TOP_OFFSET, f % 4 * CELL_X_OFFSET + TREE_LEFT_OFFSET, !0), void 0 != d[f].uT))
                for (var g = 0; g < d[f].uT.length; g += 3) b.append(ReturnDrawnArrowsStr(c, f, d[f].uT[g + 2]));
        a.html(e)
    }
    a = $("#divBonus");
    d = pathDataArray[selectedPath].bonuses;
    e = "";
    for (c = 0; c < d.length; c++) e += ReturnInteractiveTraitButton(selectedClass, selectedPath + 3, c, d[c].spriteIndex, CELL_Y_OFFSET * (c + 1) + 18, 34, !1);
    a.empty();
    a.html(e)
}

function ReturnDrawnArrowsStr(a, b, e) {
    var d, c, f, g, h = "";
    d = CELL_Y_OFFSET * Math.floor(b / 4) + TREE_TOP_OFFSET - 2;
    c = b % 4 * CELL_X_OFFSET + TREE_LEFT_OFFSET;
    if (Math.floor(b / 4) == Math.floor(e / 4)) {
        d += 16 - arrowHeight2Rows / 2;
        f = 40;
        g = e % 4 * CELL_X_OFFSET + TREE_LEFT_OFFSET + 20 - c;
        for (var k = 0; k < g; k++) h += "&#8658;";
        h += b1 + h
    } else
        for (c += 16 - arrowWidth2Cols / 2, f = CELL_Y_OFFSET * Math.floor(e / 4) + TREE_TOP_OFFSET + 20 - d, g = 40, k = 0; k < f; k++) h += "&#8659;&#8659;<br>";
    return "<div class='arrows gray' id='" + a + "a" + b + "a" + e + "' style='top: " + d + "px; left: " +
        c + "px; max-height: " + f + "px; max-width: " + g + "px; overflow:hidden'>" + h + "</div>"
}

function TR(a) {
    return RS(3, "Rank " + a) + b1
}

function RS(a, b) {
    return "<span class='ttF" + a.toString() + "'>" + b + "</span>"
}

function TTReturnPointCost(a) {
    return selectedPath == a ? b2 + RS(2, "1 Point to Next Rank") : b2 + RS(2, "2 Points to Next Rank")
}

function TTReturnWardenMinis(a) {
    for (var b = " ", e = 0; e < a.length; e++) b += "<div class='ttMiniIcon' style='background-image: url(\"img/lttpWardenMini.png\"); background-position: " + -14 * a[e] + "px 0px'></div> ";
    return b
}

function TTReturnRKMinis(a) {
    for (var b = " ", e = 0; e < a.length; e++) b += "<div class='ttMiniIconRK' style='background-image: url(\"img/lttpRKMini.png\"); background-position: " + -16 * a[e] + "px 0px'></div> ";
    return b
}

function TTReturnRequirements(a, b, e, d) {
    var c = "";
    3 < a && (a = 3);
    0 != b && b > traitsBought[a] && (c += "You need " + (b - traitsBought[a]) + " more rank", b - 1 != traitsBought[a] && (c += "s"), c = 3 == a ? c + (" earned in the whole tree (" + traitsBought[a] + "/" + b + ")") : c + (" in other traits in the " + pathDataArray[a].n + " branch (" + traitsBought[a] + "/" + b + ")"), c = b2 + RS(4, c));
    if (null != e)
        for (a = 0; a < e.length; a += 3) traitDataArray[e[a + 1]][e[a + 2]].tier < d[a / 3] && (0 == c.length && (c += b2), c += "<span class='ttF4'><br>Requires Traits<br>" + traitDataArray[e[a +
            1]][e[a + 2]].n + " at Rank " + d[a / 3] + "</span>");
    return c
}

function CreateTooltipClassButton(a, b) {
    var e = "<div style='text-align:center'>" + RS(9, classes[a]) + RS(15, "<br>Updated for<br>" + versionsArray[classesUpToVersion[a][classesUpToVersion[a].length - 1]] + "</div>"),
        d = $(b);
    $ttOtherDivRef.empty();
    $ttOtherDivRef.css("min-width", 120);
    $ttOtherDivRef.html(e);
    e = d.offset().left + d.outerWidth() / 2 - $ttOtherDivRef.outerWidth() / 2;
    d = d.offset().top + d.outerHeight(!0) + 5;
    $ttOtherDivRef.css({
        top: d + "px",
        left: e + "px"
    });
    $ttOtherDivRef.show()
}

function GetTooltipImage(a) {
    return "<div class='ttImg' style='background-image: url(\"img/lttp" + selectedClass + "v" + classesUpToVersion[selectedClass][selectedVersion] + '.png"); background-position: ' + -32 * a + "px 0px'></div>"
}

function CreateTooltip(a, b, e) {
    !autoHideTT && 7 > a && RemoveTooltip(!0);
    var d, c = "",
        f, g;
    g = 0;
    if (0 <= a && 3 > a) {
        f = ttRefsArray[0];
        f.empty();
        d = traitDataArray[a][b];
        c += GetTooltipImage(d.spriteIndex);
        g = RS(9, d.n);
        c += "<div class='ttName' style='top:" + (GetTopOffsetForTitle(g, 150, 32) + 10) + "px'>" + g + "</div><br><br><br>";
        c = 0 == d.tier ? c + RS(10, "Not earned") : c + RS(10, "Rank: " + d.tier);
        c += b2 + RS(11, d.tS[0]);
        c = void 0 != d.rT ? c + TTReturnRequirements(d.path, 5 * d.row, d.rT, d.rR) : c + TTReturnRequirements(d.path, 5 * d.row, null, null);
        0 < d.tier &&
            (c += b2 + d.tt1(d.tier));
        if (d.tier < d.tM)
            for (c += TTReturnPointCost(d.path), g = d.tier + 1; g <= d.tM; g++) c += d.tt2(g);
        150 > lastTTTitleWidth && f.css("min-width", lastTTTitleWidth + 40);
        f.html(c)
    } else 3 <= a && 6 > a ? (f = ttRefsArray[0], f.empty(), d = pathDataArray[a - 3].bonuses[b], c += GetTooltipImage(d.spriteIndex), g = RS(9, d.n), c += "<div class='ttName' style='top:" + (GetTopOffsetForTitle(g, 150, 32) + 10) + "px'>" + g + "</div><br><br><br>", c = d.req <= traitsBought[3] ? c + RS(10, "Earned") : c + RS(10, "Not Earned"), c += b2 + RS(11, d.tS[0]) + d.tt1() + TTReturnRequirements(3,
        d.req, null, null), 150 > lastTTTitleWidth && f.css("min-width", lastTTTitleWidth + 40), f.html(c)) : 6 <= a && (f = ttRefsArray[a - 6], f.empty(), d = skillsOtherDataArray[b], c += GetTooltipImage(d.spriteIndex), g = RS(9, d.n), c += "<div class='ttName' style='top:" + (GetTopOffsetForTitle(g, 150, 32) + 10) + "px'>" + g + "</div><br><br><br>", c += d.tt1(), 150 > lastTTTitleWidth && f.css("min-width", lastTTTitleWidth + 40), f.html(c), void 0 != d.range && ($invDiv.empty(), $invDiv.html(RS(7, d.range)), c = "<div class='ttRange' style='left:" + (f.width() - $invDiv.width() +
        10) + "px; position: absolute'>" + RS(7, d.range) + "</div>", f.append(c)));
    g = $(e);
    c = 0;
    7 > a && (void 0 != d.linkSkill ? CalculateTTPositions(g, d.linkSkill.length + 1) : CalculateTTPositions(g, 1));
    c = g.offset().top;
    g = f.outerHeight(!0) + c + 2;
    g > $window.height() + $window.scrollTop() && (c -= g - ($window.height() + $window.scrollTop()));
    0 > c && (c = 2);
    0 < tooltipsGoWhere.length && (f.css({
        top: c + "px",
        left: tooltipsGoWhere[0] + "px"
    }), tooltipsGoWhere.splice(0, 1));
    f.show();
    if (void 0 != d.linkSkill)
        for (a = 0; a < d.linkSkill.length; a++) CreateTooltip(a +
            7, d.linkSkill[a], e)
}

function CalculateTTPositions(a, b) {
    var e, d = $window.scrollLeft(),
        c = $window.width(),
        f = d + c,
        g = a.outerWidth(!0),
        h = a.offset().left + g / 2;
    tooltipsGoWhere = [];
    h > d + c / 2 ? (e = !0, c = h - g / 2 - 244, c < d && (e = !1, c = h + g / 2 + 2)) : (e = !1, c = h + g / 2 + 2);
    tooltipsGoWhere.push(c);
    if (!(b <= tooltipsGoWhere.length)) {
        if (e) {
            for (; c - 244 > d;) c -= 244, tooltipsGoWhere.push(c);
            e = !1;
            c = h + g / 2 + 2 - 244
        } else {
            for (; c + 488 - 2 < f;) c += 244, tooltipsGoWhere.push(c);
            e = !0;
            c = h - g / 2
        }
        if (!(b <= tooltipsGoWhere.length)) {
            if (e)
                for (; c - 244 > d;) c -= 244, tooltipsGoWhere.push(c);
            else
                for (; c +
                    488 - 2 < f;) c += 244, tooltipsGoWhere.push(c);
            if (!(b <= tooltipsGoWhere.length)) {
                for (d = c = 0; d < tooltipsGoWhere.length; d++) tooltipsGoWhere[d] > c && (c = tooltipsGoWhere[d]);
                c < h + g / 2 + 2 - 244 && (c = h + g / 2 + 2 - 244);
                for (d = tooltipsGoWhere.length; d < b; d++) c += 244, tooltipsGoWhere.push(c)
            }
        }
    }
}

function RemoveTooltip(a) {
    if (autoHideTT || !0 == a) {
        for (a = 0; a < ttRefsArray.length; a++) ttRefsArray[a].hide();
        $ttOtherDivRef.hide()
    }
}

function CanUnpressTrait(a, b) {
    for (var e = 0, d = Math.floor(b / 4), c = 0, f = 0, g = traitsBoughtPerTier[a].length - 1; 0 <= g; g--)
        if (0 < traitsBoughtPerTier[a][g]) {
            e = g;
            c = 5 * g;
            break
        }
    for (g = 0; g < e; g++)
        if (f += traitsBoughtPerTier[a][g], d <= g && f <= 5 * (g + 1)) return !1;
    return f < c ? !1 : !0
}

function TraitUnpressed(a, b, e) {
    var d = traitDataArray[a][b];
    if (0 < d.tier && CanUnpressTrait(a, b)) {
        if (void 0 != d.uT)
            for (var c = 0; c < d.uT.length; c += 3)
                if (0 < traitDataArray[d.uT[c + 1]][d.uT[c + 2]].tier && d.tier <= d.uR[Math.floor(c / 3)]) return;
        TreeTraitCountChange(-1, a, b);
        if (void 0 != d.uT)
            for (c = 0; c < d.uT.length; c += 3) d.tier + 1 == d.uR[c / 3] && ($("#" + a + "a" + b + "a" + d.uT[c + 2]).toggleClass("gray", !0), UpdateTraitState(d.uT[c + 1], d.uT[c + 2]));
        CreateTooltip(a, b, e)
    }
}

function TraitPressed(a, b, e) {
    if (pressReducesRank) TraitUnpressed(a, b, e);
    else {
        bypassGlobalClickOnce = !0;
        var d = traitDataArray[a][b];
        if (!(2 > pointsFree && a != selectedPath || 1 > pointsFree && a == selectedPath || !(d.tier < d.tM) || 5 * Math.floor(b / 4) > traitsBought[a])) {
            if (void 0 != d.rT)
                for (var c = 0; c < d.rT.length; c += 3)
                    if (traitDataArray[d.rT[c + 1]][d.rT[c + 2]].tier < d.rR[c / 3]) return;
            TreeTraitCountChange(1, a, b);
            if (void 0 != d.uT)
                for (c = 0; c < d.uT.length; c += 3) d.tier == d.uR[c / 3] && ($("#" + a + "a" + b + "a" + d.uT[c + 2]).toggleClass("gray", !1), UpdateTraitState(d.uT[c +
                    1], d.uT[c + 2]));
            CreateTooltip(a, b, e)
        }
    }
}

function TreeTraitCountChange(a, b, e) {
    traitDataArray[b][e].tier += a;
    traitsBoughtPerTier[b][Math.floor(e / 4)] += a;
    traitsBought[b] += a;
    traitsBought[3] += a;
    pointsFree = b == selectedPath ? pointsFree - a : pointsFree - 2 * a;
    UpdateTraitState(b, e);
    0 > a ? (0 == (traitsBought[b] - a) % 5 && UpdateRowState(b, Math.floor((traitsBought[b] - a) / 5)), 4 < traitsBought[3] - a && 36 > traitsBought[3] - a && 0 == (traitsBought[3] - a) % 5 && $("#" + selectedClass + (selectedPath + 3) + (Math.floor((traitsBought[3] - a) / 5) - 1)).toggleClass("gray", !0)) : 0 < traitsBought[b] && (0 == traitsBought[b] %
        5 && UpdateRowState(b, Math.floor(traitsBought[b] / 5)), 4 < traitsBought[3] && 36 > traitsBought[3] && 0 == traitsBought[3] % 5 && $("#" + selectedClass + (selectedPath + 3) + Math.floor(traitsBought[3] / 5 - 1)).toggleClass("gray", !1));
    SetTreeBGGlow(b);
    UpdatePointCounters()
}

function UpdatePointCounters() {
    $divlowpointsNStuffArray[3].empty();
    $divlowpointsNStuffArray[3].html(RS(15, "Available:<br>") + RS(19, pointsFree.toString()))
}

function ResetTraitPoints(a, b, e) {
    for (RemoveTooltip(!0); b < e; b++) {
        for (var d = 0; d < traitsBoughtPerTier[b].length; d++) traitsBoughtPerTier[b][d] = 0;
        traitsBought[3] -= traitsBought[b];
        pointsFree = b == selectedPath ? pointsFree + traitsBought[b] : pointsFree + 2 * traitsBought[b];
        traitsBought[b] = 0;
        SetTreeBGGlow(b);
        UpdatePointCounters();
        a = traitDataArray[b];
        for (d = 0; d < a.length; d++) "" != a[d].n && (a[d].tier = 0), UpdateTraitState(b, d);
        $("#divTreeA" + b).find("div").toggleClass("gray", !0)
    }
    if (!pathSelectScreenB)
        for (b = 5; 36 > b; b += 5) traitsBought[3] <
            b && $("#" + selectedClass + (selectedPath + 3) + (Math.floor(b / 5) - 1)).toggleClass("gray", !0)
}

function UpdateRowState(a, b) {
    var e = traitDataArray[a];
    b = 4 * (b + 1);
    28 == b && (b = e.length);
    if (!(28 < b))
        for (var d = b - 4; d < b; d++) void 0 != e[d] && "" != e[d].n && UpdateTraitState(a, d)
}

function UpdateTraitState(a, b) {
    var e = traitDataArray[a][b],
        d = !0;
    if ("" == e.n) d = !1;
    else if (5 * Math.floor(b / 4) <= traitsBought[a]) {
        if (void 0 != e.rT)
            for (var c = 0; c < e.rT.length; c += 3) traitDataArray[e.rT[c + 1]][e.rT[c + 2]].tier < e.rR[c / 3] && (d = !1)
    } else d = !1;
    SetTraitState(a, b, d)
}

function SetAllTreeBGGlow() {
    $divTreeGArray[0].css("height", traitsBought[0] * CELL_Y_OFFSET / 5 + 18 + "px");
    $divTreeGArray[1].css("height", traitsBought[1] * CELL_Y_OFFSET / 5 + 18 + "px");
    $divTreeGArray[2].css("height", traitsBought[2] * CELL_Y_OFFSET / 5 + 18 + "px");
    $divTreeGArray[3].css("height", traitsBought[3] * CELL_Y_OFFSET / 5 + 18 + "px")
}

function SetTreeBGGlow(a) {
    $divTreeGArray[a].css("height", traitsBought[a] * CELL_Y_OFFSET / 5 + 18 + "px");
    $divTreeGArray[3].css("height", traitsBought[3] * CELL_Y_OFFSET / 5 + 18 + "px")
}

function RemoveTreeBGGlow(a) {
    $divTreeGArray[a].css("height", "0px");
    $divTreeGArray[3].css("height", "0px")
}

function SetTraitState(a, b, e) {
    e ? (e = traitDataArray[a][b], 0 == e.tier ? traitStates[a][b][2].css("background-color", "rgba(0, 0, 0, .9)") : e.tier == e.tM ? traitStates[a][b][2].css("background-color", "rgba(255, 255, 255, .5)") : traitStates[a][b][2].css("background-color", "rgba(255, 255, 255, .3)"), traitStates[a][b][2].empty(), traitStates[a][b][2].html(RS(18, e.tier + "/" + e.tM)), traitStates[a][b][2].show(), $("#" + selectedClass + a + b).toggleClass("gray", !1)) : (traitStates[a][b][2].hide(), $("#" + selectedClass + a + b).toggleClass("gray", !0))
}

function UpdateAllTraitStates() {
    for (var a = 0; 3 > a; a++)
        for (var b = 0; 28 > b; b++) pathSelectScreenB || void 0 == traitDataArray[a][b] ? traitStates[a][b][2].hide() : UpdateTraitState(a, b);
    if (!pathSelectScreenB)
        for (a = 0; 7 > a; a++) 5 * (a + 1) > traitsBought[3] ? $("#" + selectedClass + (selectedPath + 3) + a).toggleClass("gray", !0) : $("#" + selectedClass + (selectedPath + 3) + a).toggleClass("gray", !1)
}

function DisableAllTraitStates() {
    for (var a = 0; 3 > a; a++)
        for (var b = 0; 28 > b; b++) traitStates[a][b][2].hide()
}

function PickClassButtonPressed(a) {
    LoadClassDB(a, classesUpToVersion[a].length - 1)
}

function PickClass(a) {
    -1 == a ? (a = selectedClass, ResetTraitPoints(a, 0, 3)) : -1 != selectedClass && ResetTraitPoints(selectedClass, 0, 3);
    classPickedOnce || (classPickedOnce = !0, $("#classPickTxt").remove());
    pathSelectScreenB = !0;
    ConstructPaths();
    UpdateAllTraitStates();
    RemoveTreeBGGlow(0);
    RemoveTreeBGGlow(1);
    RemoveTreeBGGlow(2);
    RemoveTreeBGGlow(3);
    for (a = 0; 3 > a; a++) $divLowPickPathArray[a].show();
    for (a = 0; 4 > a; a++) $divlowpointsNStuffArray[a].hide();
    PickClassVisibility(!1);
    RefreshVersionSelection()
}

function RefreshVersionSelection() {
    $selectVersionScroll.empty();
    for (var a = "", b = 0; b < classesUpToVersion[selectedClass].length; b++) a += "<option value=" + classesUpToVersion[selectedClass][b] + ">" + versionsArray[classesUpToVersion[selectedClass][b]] + "</option>";
    $selectVersionScroll.html(a).val(classesUpToVersion[selectedClass][selectedVersion]);
    a = "" + classes[selectedClass] + " " + versionsArray[classesUpToVersion[selectedClass][selectedVersion]];
    a = classesUpToVersion[selectedClass][selectedVersion] != latestGameVersion ?
        a + " (OLD)" : a + " (Latest)";
    $("#divVersionInfo").html(a).css("color", "#FFFFFF")
}

function PickClassVisibility(a) {
    a ? ($("#divClassButtons").show(), $("#divChangeClassVersion").hide()) : ($("#divClassButtons").hide(), $("#divChangeClassVersion").show())
}

function PickSpec(a) {
    selectedPath = a;
    ConstructTrees();
    pathSelectScreenB = !1;
    UpdateAllTraitStates();
    SetTreeBGGlow(0);
    SetTreeBGGlow(1);
    SetTreeBGGlow(2);
    SetTreeBGGlow(3);
    for (var b = 0; 3 > b; b++) $divLowPickPathArray[b].hide();
    for (b = 0; 4 > b; b++) $divlowpointsNStuffArray[b].show();
    UpdatePointCounters();
    0 == a ? $("#divVersionInfo").css("color", "#3553FF") : 1 == a ? $("#divVersionInfo").css("color", "#FF0000") : 2 == a && $("#divVersionInfo").css("color", "#FEFF00")
}

function StringifyBuild() {}

function LoadBuild(a, b) {
    if (buildFromUrlLoading) {
        for (var e = 0; e < classesUpToVersion[b].length; e++)
            if (classesUpToVersion[b][e] == a.version) {
                buildFromUrlCache = a;
                LoadClassDB(b, e);
                return
            }
        buildFromUrlLoading = !1
    } else PickSpec(a.path), LoadBuild_Tree(a.b, traitDataArray[0], 0), LoadBuild_Tree(a.r, traitDataArray[1], 1), LoadBuild_Tree(a.y, traitDataArray[2], 2), UpdateAllTraitStates(), SetAllTreeBGGlow(), UpdatePointCounters()
}

function LoadBuild_Tree(a, b, e) {
    for (var d = 0; d < b.length; d++)
        if (pointsFree = e == selectedPath ? pointsFree - a[d] : pointsFree - 2 * a[d], traitsBought[e] += a[d], traitsBought[3] += a[d], traitsBoughtPerTier[e][Math.floor(d / 4)] += a[d], b[d].tier = a[d], void 0 != b[d].uT)
            for (var c = 0; c < b[d].uT.length; c += 3) b[d].tier >= b[d].uR[c / 3] && $("#" + e + "a" + d + "a" + b[d].uT[c + 2]).toggleClass("gray", !1)
}

function StrNotCorrupted(a, b) {
    return "string" == typeof uBuild[a] && (b && (uBuild[a] = LZString.decompressFromBase64(uBuild[a])), IsStrDigits(uBuild[a])) ? !0 : !1
}

function IsStrDigits(a) {
    return /^\d+$/.test(a)
}

function LoadBuildFromCodePressed() {
    0 == $shortCodeInput.val().length ? $shortCodeInput.val("Input Build Code Here!") : LoadBuildFromCode($shortCodeInput.val())
}

function LoadBuildFromCode(a) {
    $shortCodeInput.val("Loading...");
    $.ajax({
        url: "reqLongLink.php?c=" + a,
        context: document.body
    }).done(function(a) {
        ShortLinkBuildLoadingDone($(a).find("#divCode").html().slice(1))
    }).fail(function() {
        $shortCodeInput.val("Unknown Code")
    })
}

function ShortLinkBuildLoadingDone(a) {
    4 < a.length && "?lb=" == a.substring(0, 4) ? window.location.href = document.URL.substring(0, document.URL.indexOf(".php") + 4) + LZString.decompressFromBase64(a.substring(4)) : $textAreaLog.val("Not a proper build link")
}

function LoadBuildFromURL() {
    if ("none" != sCode) LoadBuildFromCode(sCode);
    else {
        var a = !0;
        if ("-1" != uBuild[0]) {
            !a || StrNotCorrupted(0, !1) && 1 == uBuild[0].length || (a = !1);
            !a || StrNotCorrupted(1, !1) && 1 == uBuild[1].length || (a = !1);
            a && (!StrNotCorrupted(2, !1) || 2 < uBuild[2].length) && (a = !1);
            for (var b = 3; 6 > b; b++) !a || StrNotCorrupted(b, !0) && 28 == uBuild[b].length || (a = !1);
            if (a) {
                a = {
                    n: ""
                };
                a.path = parseInt(uBuild[1]);
                a.version = parseInt(uBuild[2]);
                a.b = [];
                for (b = 0; b < uBuild[3].length; b++) a.b.push(parseInt(uBuild[3].substr(b, 1)));
                a.r = [];
                for (b = 0; b < uBuild[4].length; b++) a.r.push(parseInt(uBuild[4].substr(b, 1)));
                a.y = [];
                for (b = 0; b < uBuild[5].length; b++) a.y.push(parseInt(uBuild[5].substr(b, 1)));
                buildFromUrlLoading = !0;
                LoadBuild(a, parseInt(uBuild[0]))
            } else TAAppend("Build in URL corrupted!\nProbably a part of it got lost while copying.")
        }
    }
}

function TAAppend(a) {
    $textAreaLog.val($textAreaLog.val() + a + "\n");
    1500 < $textAreaLog.val().length && $textAreaLog.val($textAreaLog.val().slice($textAreaLog.val().length - 1500, $textAreaLog.val().length));
    $textAreaLog.scrollTop($textAreaLog[0].scrollHeight)
}

function ChangeSLOptVisibility() {
    var a = $("#tdSLOpt");
    "none" == a.css("display") ? a.show() : a.hide()
}

function ChangeClassVisibility() {
    var a = $("#tdClass");
    "none" == a.css("display") ? a.show() : a.hide()
}

function ChangePressReduceRank() {
    var a = $("#removeRankCheckBox");
    a.attr("checked") ? (pressReducesRank = !1, a.attr("checked", !1)) : (pressReducesRank = !0, RemoveTooltip(!0), a.attr("checked", !0))
}

function ChangeTTAutoHide() {
    var a = $("#ttAutoHideCheckBox");
    a.attr("checked") ? (autoHideTT = !1, a.attr("checked", !1)) : (autoHideTT = !0, RemoveTooltip(!0), a.attr("checked", !0))
}

function ClearTALog() {
    $textAreaLog.val("")
}

function CreateBuildURL() {
    "none" == $("#tdSLOpt").css("display") && ChangeSLOptVisibility();
    if (pathSelectScreenB) TAAppend("You need to select class and path to save a build.");
    else {
        for (var a = "?lcl=" + selectedClass + "&lpa=" + selectedPath + "&lve=" + classesUpToVersion[selectedClass][selectedVersion], b, e = 0; 3 > e; e++) {
            a += "&lp" + e + "=";
            b = traitDataArray[e];
            for (var d = "", c = 0; c < b.length; c++) d = "" == b[c].n ? d + 0 : d + b[c].tier;
            a += LZString.compressToBase64(d)
        }
        var f = a,
            a = document.URL.substring(0, document.URL.indexOf(".php") + 4) + "?lb=" +
            LZString.compressToBase64(a);
        $textAreaLog.val("Creating...\nPlease Wait...");
        $.ajax({
            url: "reqShortLink.php?c=" + a,
            context: document.body
        }).done(function(a) {
            a = $(a).find("#divCode").html().slice(1);
            10 < a.length ? $textAreaLog.val("Something went wrong\nPlease report this error:\n" + f) : $textAreaLog.val("Your build link is:\n" + document.URL.substring(0, document.URL.indexOf(".php") + 4) + "?c=" + a + "\nYour case sensitive build Code is:\n" + a + "\nCopy it and share this build with your friends.\nThe Code can be loaded from field on this page.")
        }).fail(function() {
            $textAreaLog.val("Something went wrong")
        })
    }
}

function LTTPSaveLocalStorage() {
    localStorage.setItem("LTTPBuilds", LZString.compressToUTF16(JSON.stringify(savedBuilds)))
}

function LTTPLoadLocalStorage() {
    var a = localStorage.getItem("LTTPBuilds");
    if (null == a)
        for (savedBuilds = [], a = 0; a < classes.length; a++) savedBuilds.push([]);
    else savedBuilds = JSON.parse(LZString.decompressFromUTF16(a))
}

function ClickedAnywhere() {
    bypassGlobalClickOnce ? bypassGlobalClickOnce = !1 : autoHideTT || RemoveTooltip(!0)
}

function LTTPInit() {
    $window = $(window);
    $invDiv = $("#invDiv");
    for (var a = 0; 7 > a; a++) $invDiv.after('<div class="toolTip" id="toolTip' + (a + 1) + '"></div>'), ttRefsArray.push($("#toolTip" + (a + 1)));
    $ttOtherDivRef = $("#toolTipOther");
    $divTreeGArray.push($("#divTreeG0"), $("#divTreeG1"), $("#divTreeG2"), $("#divTreeG3"));
    $textAreaLog = $("#textAreaLog");
    $shortCodeInput = $("#shortCodeInput");
    $selectVersionScroll = $("#selectVersionScroll");
    $("#mobileLink").attr("href", "lM.php" + document.URL.substring(document.URL.indexOf(".php") +
        4));
    $(document).click(function() {
        ClickedAnywhere()
    });
    $invDiv.hide();
    RemoveTooltip(!0);
    for (var b, e, d, a = 0; 3 > a; a++) {
        b = $("#divTreeT" + a);
        e = "";
        for (var c = 0; 28 > c; c++) d = "tx" + a + c, e = "<div class='traitNumNGlow' id='" + d + "' style='top: " + (CELL_Y_OFFSET * Math.floor(c / 4) + TREE_TOP_OFFSET - 2) + "px; left:" + (c % 4 * CELL_X_OFFSET + TREE_LEFT_OFFSET - 2) + "px'></div>", b.append(e), traitStates[a].push([!1, !1, $("#" + d)])
    }
    for (a = 0; 3 > a; a++) {
        b = $("#divTree" + a);
        e = "<div id='divTreeLines" + a + "' style='position: absolute'>";
        for (c = 1; 7 > c; c++) e +=
            "<div class='grayLines' style='top:" + (TREE_TOP_OFFSET + CELL_Y_OFFSET * c - 6) + "px; left: 0px; background-image: url(\"img/lttpOther.png\"); background-position: 0px 0px'></div><div class='grayLines' style='top:" + (TREE_TOP_OFFSET + CELL_Y_OFFSET * c - 6) + 'px; left: 200px; background-image: url("img/lttpOther.png"); background-position: 0px -1px\'></div>';
        b.after(e + "</div>");
        b = $("#divTreeLines" + a).hide()
    }
    b = $("#divClassButtons");
    e = "";
    d = [];
    for (a = 0; a < classes.length; a++) {
        e = "";
        classDBCache.push([]);
        classDBCacheState.push([]);
        for (c = 0; c < classesUpToVersion[a].length; c++) classDBCache[a].push([]), classDBCacheState[a].push(-1);
        c = "cSel" + a;
        e += "<input type='button' class='button1' id='" + c + "' onclick='PickClassButtonPressed(" + a + ");' onmouseover='CreateTooltipClassButton(" + a + ',"#' + c + "\")' onmouseout='TTOtherHide()' value='" + classes[a] + "'";
        0 == classesEnabled[a] && (e += "disabled='disabled'");
        e += ">";
        d.push("" + e)
    }
    e = "";
    for (a = 0; a < classButtonOrder.length; a++) null != d[classButtonOrder[a]] && "" != d[classButtonOrder[a]] && (e += d[classButtonOrder[a]]);
    b.html(e + "<div id='classPickTxt'><span class='ttF19'><b>Choose your class.</b></span></div>");
    for (a = 0; 3 > a; a++) $divLowPickPathArray.push($("#divLowPickPath" + a)), $divLowPickPathArray[a].html("<input type='button' class='button1' onclick='PickSpec(" + a + ");' value='Pick Path'>"), $divLowPickPathArray[a].hide();
    for (a = 0; 4 > a; a++) $divlowpointsNStuffArray.push($("#divLow" + a)), 3 > a && ($divlowpointsNStuffArray[a].html("<input type='button' class='button1' onclick='ResetTraitPoints(-1," + a + "," + (a + 1) + ");' value='Reset Tree'>"),
        2 == a && $divlowpointsNStuffArray[a].append("<br><input type='button' class='button1' onclick='PickClass(-1);' value='Re-specialize'>")), $divlowpointsNStuffArray[a].hide();
    $invDiv.empty();
    $invDiv.html("<div class='arrows' style='display: block; position: static'>&#8659;&#8659;<br>&#8659;&#8659;</div>");
    arrowWidth2Cols = $invDiv.width();
    $invDiv.empty();
    $invDiv.html("<div class='arrows' style='display: block; position: static'>&#8658;&#8658;<br>&#8658;&#8658;</div>");
    arrowHeight2Rows = $invDiv.height();
    b.offset();
    b.outerHeight(!0);
    $window.height();
    $window.scrollTop();
    LoadBuildFromURL();
    UpdateAllTraitStates()
}

function TTOtherHide() {
    $ttOtherDivRef.hide()
}

function LoadClassDB(a, b) {
    1 == classDBCacheState[a][b] ? (0 <= selectedClass && ResetTraitPoints(), pathDataArray = classDBCache[a][b][0], traitDataArray = classDBCache[a][b][1], skillsOtherDataArray = classDBCache[a][b][2], selectedClass = a, selectedVersion = b, PickClass(a), buildFromUrlLoading && (buildFromUrlLoading = !1, LoadBuild(buildFromUrlCache, a))) : 0 != classDBCacheState[a][b] && -1 == classDBCacheState[a][b] && (classDBCacheState[a][b] = 0, ImportClassDBBegin(a, b))
}

function ImportClassDBBegin(a, b) {
    $.getScript("js/lotrottp" + a + "v" + classesUpToVersion[a][b] + ".js", function() {
        ImportedInit(a, b);
        ImportClassDBFinish(a, b)
    })
}

function ImportClassDBFinish(a, b) {
    for (var e = 0, d = classDBCache[a][b], c = 0; c < d[1].length; c++) {
        for (; 28 > d[1][c].length;) d[1][c].push({
            n: ""
        });
        for (var f = 0; 28 > f; f++) "" != d[1][c][f].n && (d[1][c][f].spriteIndex = e, e++, d[1][c][f].path = c, d[1][c][f].row = Math.floor(f / 4), d[1][c][f].tier = 0)
    }
    for (c = 0; c < d[0].length; c++)
        for (f = 0; f < d[0][c].bonuses.length; f++) d[0][c].bonuses[f].spriteIndex = e, e++, d[0][c].bonuses[f].path = c, d[0][c].bonuses[f].req = 5 * (f + 1);
    for (c = 0; c < d[2].length; c++) d[2][c].spriteIndex = e, e++;
    classDBCacheState[a][b] =
        1;
    LoadClassDB(a, b)
}

function ChangeVersionPressed() {
    var a = $selectVersionScroll.val();
    for (i = 0; i < classesUpToVersion[selectedClass].length; i++) a == classesUpToVersion[selectedClass][i] && LoadClassDB(selectedClass, i)
};