function loaded() {
    $(".tableField").bind("input propertychange", function() { neighborhoodChange(); });
    $(".tableLong").bind("input propertychange", function() { neighborhoodChange(); });
}

function removeSelectedNeighborhood() {
    gid = $(".altSelected").data("id");

    $.ajax({
        type     : "POST",
        cache    : false,
        url      : "action.php",
        data     : {action: "removeNeighborhood",
                    id: gid},
        success  : function(data) {
            if(handleResult(data)) {
                element = null;

                $(".neighborhood-entry").each(function() {
                    if($(this).data("id") == gid) {
                        element = $(this);
                    }
                });

                element.remove();

                neighborhoodDeselect();
            }
        }
    });
}

function neighborhoodDeselect() {
    $(".neighborhood-entry").removeClass("altSelected");

    $(".updateButton").attr("disabled", "disabled");
    $(".updateButton").addClass("disabledButton");
    $(".addButton").addClass("disabledButton");
    $(".tableField").data("old", "");
    $(".tableField").val("");
    $(".tableField").attr("disabled", "disabled");
    $(".tableLong").data("old", "");
    $(".tableLong").val("");
    $(".tableLong").attr("disabled", "disabled");
    $(".catList").data("changed", "false");
    $(".catList").html("");
    $(".photoList").data("changed", "false");
    $(".photoList").html("");

    if($("#neighborhoodRemove").length > 0) {
        $("#neighborhoodRemove").remove();
    }
}

function neighborhoodSelect(element) {
    $(".neighborhood-entry").removeClass("altSelected");
    $(element).addClass("altSelected");

    $(".updateButton").attr("disabled", "disabled");
    $(".updateButton").addClass("disabledButton");
    $(".tableField").removeAttr("disabled");
    $(".tableLong").removeAttr("disabled");
    $(".addButton").removeClass("disabledButton");

    fillNeighborhoodTables();

    setNeighborhoodButtons($(element));
}

function fillNeighborhoodTables() {
    gid = $(".altSelected").data("id");

    $.ajax({
        type     : "POST",
        cache    : false,
        url      : "action.php",
        data     : {action: "pullNeighborhoodData",
                    id: gid},
        success  : function(data) {
            if(handleResult(data)) {
                res = data.split("<br>");

                for (i = 0; i < res.length; i++) {
                    subres = res[i].split("==");
                    $("#" + subres[0]).val(subres[1]);
                    $("#" + subres[0]).data("old", subres[1]);
                }
            }
        }
    });
}

function setNeighborhoodButtons(element) {
    if($("#neighborhoodRemove").length == 0) {
        $(".neighborhood-list").append("<div class=\"floating-move-button remove \" id=\"neighborhoodRemove\" onclick=\"promptConfirm('removeSelectedNeighborhood()', 'Are you sure you want to remove this entry from the things to do?')\"></div>");
    }

    $("#neighborhoodRemove").css("left", element.position().left + - $("#neighborhoodRemove").width() - 2);
    $("#neighborhoodRemove").css("top", element.position().top + ($("#neighborhoodRemove").height() / 2) + 1);
}

function neighborhoodChange() {
    anythingChanged = false;

    if($("#neighborhoodName").val() != $("#neighborhoodName").data("old")) anythingChanged = true;
    if($("#neighborhoodMap").val() != $("#neighborhoodMap").data("old")) anythingChanged = true;
    if($("#neighborhoodSite").val() != $("#neighborhoodSite").data("old")) anythingChanged = true;
    if($("#neighborhoodDesc").val() != $("#neighborhoodDesc").data("old")) anythingChanged = true;
    if($(".catList").data("changed") == "true") anythingChanged = true;
    if($(".photoList").data("changed") == "true") anythingChanged = true;

    if(!anythingChanged) {
        $(".updateButton").attr("disabled", "disabled");
        $(".updateButton").addClass("disabledButton");
    } else {
        $(".updateButton").removeAttr("disabled");
        $(".updateButton").removeClass("disabledButton");
    }
}