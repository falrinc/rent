function loaded() {
    $(".tableField").bind("input propertychange", function() { neighborhoodChange(); });
    $(".tableLong").bind("input propertychange", function() { neighborhoodChange(); });
}

function neighborhoodUpdate() {
    gid = $(".altSelected").data("id");
    gName = $("#neighborhoodName").val();
    gMap = $("#neighborhoodMap").val();
    gSite = $("#neighborhoodSite").val();
    gDesc = $("#neighborhoodDesc").val();

    gCat = [];

    $(".catListEntry").each(function() {
        $(this).find(".subField").each(function() {
            gCat.push($(this).val());
        });
    });

    gCover = "";
    gPhoto = [];

    $(".photoListEntry").each(function() {
        if($(this).hasClass("coverPhoto")) {
            $(this).find("img").each(function() {
                gCover = $(this).attr("src");
            });
        } else {
            $(this).find("img").each(function() {
                gPhoto.push($(this).attr("src"));
            });
        }
    });

    $.ajax({
        type     : "POST",
        cache    : false,
        url      : "action.php",
        data     : {action: "updateNeighborhood",
                    id: gid,
                    name: gName,
                    map: gMap,
                    site: gSite,
                    desc: gDesc,
                    cover: gCover,
                    cats: gCat,
                    photos: gPhoto},
        success  : function(data) {
            if(handleResult(data)) {
                element = null;

                $(".neighborhood-entry").each(function() {
                    if($(this).data("id") == gid) {
                        element = $(this);
                    }
                });

                $(element).html(gName);

                neighborhoodDeselect();
                neighborhoodSelect(element);
            }
        }
    });
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

function gotChange(fileBox) {
    if(fileBox.value != "") {
        formdata = new FormData();
        for(i = 0; i < fileBox.files.length; i++) {
            formdata.append("image_" + i, fileBox.files[i]);
        }
        
        formdata.append("action","uploadNeighborhoodPhoto");
        formdata.append("id", $(".altSelected").data("id"));

        $.ajax({
            type     : "POST",
            cache    : false,
            contentType: false,
            processData: false,
            url      : "action.php",
            data     : formdata,
            success: function(data) {
                if(handleResult(data)) {
                    for(i = 0; i < fileBox.files.length; i++) {
                        appString = "<div class=\"photoListEntry\">";
                        appString += "<img src=\"assets/images/thingsToDo/" + fileBox.files[i].name + "\" onclick=\"setNeighborhoodCoverPhoto(this)\"/>";
                        appString += "<div class=\"static-remove-button \" onclick=\"removeNeighborhoodPhoto(this)\"></div>";
                        appString += "</div>";
                        $(".photoList").append(appString);
                    }

                    if(!$(".coverPhoto")[0]) {
                        $(".photoListEntry").first().addClass("coverPhoto");
                    }

                    $(".photoList").data("changed", "true");
                    neighborhoodChange();
                }
                fileBox.value = "";
            }
        });
    }
}

function neighborhoodAddPhoto() {
    if($("#neighborhoodPhotoAddButton").hasClass("disabledButton")) return;

    document.getElementById('uploadPhoto').click();
    return;
}

function neighborhoodCreate() {
    if($("#inputName").val() == "") return;

    fullName = $("#inputName").val();

    $.ajax({
        type     : "POST",
        cache    : false,
        url      : "action.php",
        data     : {action: "createNeighborhood",
                    name: fullName},
        success  : function(data) {
            if(handleResult(data)) {
                genID = data.split("==");
                $("#inputName").val("");

                $(".neighborhood-list").append("<div class=\"neighborhood-entry\" onclick=\"neighborhoodSelect(this)\" data-id=\"" + genID[1] + "\">" + fullName + "</div>");
            }
        }
    });

    return;
}

function neighborhoodAddCategory() {
    if($("#neighborhoodCategoryAddButton").hasClass("disabledButton")) return;

    appString = "<div class=\"catListEntry\">";
    appString += "<div class=\"static-move-button remove \" onclick=\"removeNeighborhoodCatRow(this)\"></div>";
    appString += "<input class=\"subField\" placeholder=\"Enter Category...\" data-old=\"\" value=\"\" type=\"text\" list=\"catSuggestions\" />";
    appString += "</div>";

    $(".catList").append(appString);
    $(".subField").bind("input propertychange", function() { neighborhoodChange(); });

    $(".catList").data("changed", "true");
    neighborhoodChange();
}

function removeNeighborhoodCatRow(element) {
    $(element).parent().remove();
    $(".catList").data("changed", "true");
    neighborhoodChange();
}

function removeNeighborhoodPhoto(element) {
    wasCover = false;
    if($(element).parent().hasClass("coverPhoto")) wasCover = true;
    $(element).parent().remove();
    $(".photoList").data("changed", "true");

    if(wasCover) {
        $(".photoListEntry").first().addClass("coverPhoto");
    }

    neighborhoodChange();
}

function setNeighborhoodCoverPhoto(element) {
    if($(element).parent().hasClass("coverPhoto")) return;

    $(".coverPhoto").removeClass("coverPhoto");
    $(element).parent().addClass("coverPhoto");
    $(".photoList").data("changed", "true");

    neighborhoodChange();
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
    if($(element).hasClass("altSelected")) return;
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
                    if(subres[0][0] == "\n") subres[0] = subres[0].substring(1,subres[0].length);
                    $("#" + subres[0]).val(subres[1]);
                    $("#" + subres[0]).data("old", subres[1]);
                }
            }
        }
    });

    $(".photoList").html("");
    $(".catList").html("");

    $.ajax({
        type     : "POST",
        cache    : false,
        url      : "action.php",
        data     : {action: "pullNeighborhoodPhotos",
                    id: gid},
        success  : function(data) {
            if(handleResult(data)) {
                $(".photoList").append(data);
            }
        }
    });

    $.ajax({
        type     : "POST",
        cache    : false,
        url      : "action.php",
        data     : {action: "pullNeighborhoodCategories",
                    id: gid},
        success  : function(data) {
            if(handleResult(data)) {
                $(".catList").append(data);
                $(".subField").bind("input propertychange", function() { neighborhoodChange(); });
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
    
    $(".subField").each(function() {
        if($(this).val() != $(this).data("old")) anythingChanged = true;
    });

    if(!anythingChanged) {
        $(".updateButton").attr("disabled", "disabled");
        $(".updateButton").addClass("disabledButton");
    } else {
        $(".updateButton").removeAttr("disabled");
        $(".updateButton").removeClass("disabledButton");
    }
}