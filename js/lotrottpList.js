var lttpList = {
    buildArray: null,
    versionArray: "0.0.0 12.0.1 12.1 12.2 13.0 13.1 14.0 20.0".split(" "),
    classOrder: [9, 0, 1, 2, 3, 4, 5, 6, 7, 8],
    classArray: "Burglar Captain Champion Guardian Hunter Lore-Master Minstrel Rune-Keeper Warden Beorning".split(" "),
    pathArray: ["Blue", "Red", "Yellow"],
    byClass: [
        [],
        [],
        [],
        [],
        [],
        [],
        [],
        [],
        [],
        []
    ],
    selectedClass: -1
};

function LTTPListInit() {
    var a = $("body");
    if ("undefined" == typeof bArray) a.append("<br>ERROR: array undefined");
    else {
        lttpList.buildArray = bArray;
        a.html("<div id='divClassButtons'></div><div id='divList'></div>");
        for (var a = lttpList.buildArray, b = 0; b < a.length; b++) a[b].bClass > lttpList.classArray.length || a[b].ver > lttpList.versionArray || "undefined" != typeof a[b].bShort && "" != a[b].bShort && lttpList.byClass[a[b].bClass].push(a[b]);
        for (var a = lttpList.classArray, d = "<select id='selectClassScroll' onchange='SelectClassChange(this)'><option value='-1'>Select Class</option>",
                g = [], b = 0; b < a.length; b++) g.push("<option value='" + b + "'>" + a[b] + "</option>");
        a = lttpList.classOrder;
        for (b = 0; b < a.length; b++) d += g[a[b]];
        d += "</select>";
        $("#divClassButtons").html(d)
    }
}

function SelectClassChange(a) {
    -1 != a.value && PickClass(a.value)
}

function SortByCounter(a, b) {
    return a.counter < b.counter ? 1 : a.counter > b.counter ? -1 : 0
}

function SortByVersion(a, b) {
    return a.ver < b.ver ? 1 : a.ver > b.ver ? -1 : a.counter < b.counter ? 1 : a.counter > b.counter ? -1 : 0
}

function SortByBlue(a, b) {
    return a.pB < b.pB ? 1 : a.pB > b.pB ? -1 : a.counter < b.counter ? 1 : a.counter > b.counter ? -1 : 0
}

function SortByRed(a, b) {
    return a.pR < b.pR ? 1 : a.pR > b.pR ? -1 : a.counter < b.counter ? 1 : a.counter > b.counter ? -1 : 0
}

function SortByYellow(a, b) {
    return a.pY < b.pY ? 1 : a.pY > b.pY ? -1 : a.counter < b.counter ? 1 : a.counter > b.counter ? -1 : 0
}

function PickClass(a) {
    a != lttpList.selectedClass && (lttpList.selectedClass = a, CreateTables(a, 0))
}

function CreateTables(a, b) {
    var d, g = ["colB", "colR", "colY"];
    d = "<table>" + ("<tr><td class='tdUp tdUpHover' onclick='CreateTables(" + a + ",0)'>Users</td><!--<td class='tdUp tdUpHover' onclick='CreateTables(" + a + ",1)'>Version</td>--><td class='tdUp'>Class</td><td class='tdUp'>Code</td><td class='tdUp tdUpHover' onclick='CreateTables(" + a + ",2)'>Blue</td><td class='tdUp tdUpHover' onclick='CreateTables(" + a + ",3)'>Red</td><td class='tdUp tdUpHover' onclick='CreateTables(" + a + ",4)'>Yellow</td></tr>");
    var c = lttpList.byClass[a];
    0 == b ? c.sort(SortByCounter) : 1 == b ? c.sort(SortByVersion) : 2 == b ? c.sort(SortByBlue) : 3 == b ? c.sort(SortByRed) : 4 == b && c.sort(SortByYellow);
    for (var e = 0; e < c.length; e++) {
        var h = g[c[e].path],
            f = "<a href='l.php?c=" + c[e].bShort + "' style='display:block'>";
        d += "<tr class='bRow'><td class='" + h + "'>" + f + c[e].counter + "</a></td><!--<td class='" + h + "'>" + f + lttpList.versionArray[c[e].ver] + "</a></td>--><td class='" + h + "'>" + f + lttpList.classArray[c[e].bClass] + "</a></td><td class='" + h + "'>" + f + c[e].bShort + "</a></td><td class='colB'>" + f + c[e].pB +
            "</a></td><td class='colR'>" + f + c[e].pR + "</a></td><td class='colY'>" + f + c[e].pY + "</a></td></tr>"
    }
    d += "</table>";
    $("#divList").html(d)
};