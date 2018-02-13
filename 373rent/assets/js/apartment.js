function loaded() {
    $(".tableField").bind("input propertychange", function() { apartmentChange(); });
    $(".tableLong").bind("input propertychange", function() { apartmentChange(); });
    $(".tableShort").bind("input propertychange", function() { apartmentChange(); });
}

function apartmentUpdate() {
    gid = $(".altSelected").data("id");
    gName = $("#apartmentName").val();
    gTag = $("#apartmentTag").val();
    gAddr = $("#apartmentAddr").val();
    gPrice = $("#apartmentPrice").val();
    gDesc = $("#apartmentDesc").val();
    gBed = $("#apartmentBed").val();
    gBath = $("#apartmentBath").val();
    gSqft = $("#apartmentSqft").val();
    gAvail = $("#apartmentAvail").val();

    gAmen = [];

    $(".amenListEntry").each(function() {
        $(this).find(".subField").each(function() {
            gAmen.push($(this).val());
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
        data     : {action: "updateApartment",
                    id: gid,
                    name: gName,
                    tag: gTag,
                    addr: gAddr,
                    price: gPrice,
                    desc: gDesc,
                    bed: gBed,
                    bath: gBath,
                    sqft: gSqft,
                    avail: gAvail,
                    cover: gCover,
                    amens: gAmen,
                    photos: gPhoto},
        success  : function(data) {
            if(handleResult(data)) {
                element = null;

                $(".apartment-entry").each(function() {
                    if($(this).data("id") == gid) {
                        element = $(this);
                    }
                });

                $(element).html(gName);

                apartmentDeselect();
                apartmentSelect(element);
            }
        }
    });
}

function removeSelectedApartment() {
    gid = $(".altSelected").data("id");

    $.ajax({
        type     : "POST",
        cache    : false,
        url      : "action.php",
        data     : {action: "removeApartment",
                    id: gid},
        success  : function(data) {
            if(handleResult(data)) {
                element = null;

                $(".apartment-entry").each(function() {
                    if($(this).data("id") == gid) {
                        element = $(this);
                    }
                });

                element.remove();

                apartmentDeselect();
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
        
        formdata.append("action","uploadApartmentPhoto");
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
                        appString += "<img src=\"assets/images/properties/" + fileBox.files[i].name + "\" onclick=\"setApartmentCoverPhoto(this)\"/>";
                        appString += "<div class=\"static-remove-button \" onclick=\"removeApartmentPhoto(this)\"></div>";
                        appString += "</div>";
                        $(".photoList").append(appString);
                    }

                    if(!$(".coverPhoto")[0]) {
                        $(".photoListEntry").first().addClass("coverPhoto");
                    }

                    $(".photoList").data("changed", "true");
                    apartmentChange();
                }
                fileBox.value = "";
            }
        });
    }
}

function apartmentAddPhoto() {
    if($("#apartmentPhotoAddButton").hasClass("disabledButton")) return;

    document.getElementById('uploadPhoto').click();
    return;
}

function apartmentCreate() {
    if($("#inputName").val() == "") return;

    fullName = $("#inputName").val();

    $.ajax({
        type     : "POST",
        cache    : false,
        url      : "action.php",
        data     : {action: "createApartment",
                    name: fullName},
        success  : function(data) {
            if(handleResult(data)) {
                genID = data.split("==");
                $("#inputName").val("");

                $(".apartment-list").append("<div class=\"apartment-entry\" onclick=\"apartmentSelect(this)\" data-id=\"" + genID[1] + "\">" + fullName + "</div>");
            }
        }
    });

    return;
}

function apartmentAddAmenity() {
    if($("#apartmentAmenityAddButton").hasClass("disabledButton")) return;

    appString = "<div class=\"amenListEntry\">";
    appString += "<div class=\"static-move-button remove \" onclick=\"removeApartmentAmenRow(this)\"></div>";
    appString += "<input class=\"subField\" placeholder=\"Enter Amenity...\" data-old=\"\" value=\"\" type=\"text\" list=\"amenSuggestions\" />";
    appString += "</div>";

    $(".amenList").append(appString);
    $(".subField").bind("input propertychange", function() { apartmentChange(); });

    $(".amenList").data("changed", "true");
    apartmentChange();
}

function removeApartmentAmenRow(element) {
    $(element).parent().remove();
    $(".amenList").data("changed", "true");
    apartmentChange();
}

function removeApartmentPhoto(element) {
    wasCover = false;
    if($(element).parent().hasClass("coverPhoto")) wasCover = true;
    $(element).parent().remove();
    $(".photoList").data("changed", "true");

    if(wasCover) {
        $(".photoListEntry").first().addClass("coverPhoto");
    }

    apartmentChange();
}

function setApartmentCoverPhoto(element) {
    if($(element).parent().hasClass("coverPhoto")) return;

    $(".coverPhoto").removeClass("coverPhoto");
    $(element).parent().addClass("coverPhoto");
    $(".photoList").data("changed", "true");

    apartmentChange();
}

function apartmentDeselect() {
    $(".apartment-entry").removeClass("altSelected");

    $(".updateButton").attr("disabled", "disabled");
    $(".updateButton").addClass("disabledButton");
    $(".addButton").addClass("disabledButton");
    $(".tableField").data("old", "");
    $(".tableField").val("");
    $(".tableField").attr("disabled", "disabled");
    $(".tableLong").data("old", "");
    $(".tableLong").val("");
    $(".tableLong").attr("disabled", "disabled");
    $(".tableShort").data("old", "");
    $(".tableShort").val("");
    $(".tableShort").attr("disabled", "disabled");
    $(".amenList").data("changed", "false");
    $(".amenList").html("");
    $(".photoList").data("changed", "false");
    $(".photoList").html("");

    if($("#apartmentRemove").length > 0) {
        $("#apartmentRemove").remove();
    }
}

function apartmentSelect(element) {
    if($(element).hasClass("altSelected")) return;
    $(".apartment-entry").removeClass("altSelected");
    $(element).addClass("altSelected");

    $(".updateButton").attr("disabled", "disabled");
    $(".updateButton").addClass("disabledButton");
    $(".tableField").removeAttr("disabled");
    $(".tableLong").removeAttr("disabled");
    $(".tableShort").removeAttr("disabled");
    $(".addButton").removeClass("disabledButton");

    fillApartmentTables();

    setApartmentButtons($(element));
}

function fillApartmentTables() {
    gid = $(".altSelected").data("id");

    $.ajax({
        type     : "POST",
        cache    : false,
        url      : "action.php",
        data     : {action: "pullApartmentData",
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

    $(".photoList").html("");
    $(".amenList").html("");

    $.ajax({
        type     : "POST",
        cache    : false,
        url      : "action.php",
        data     : {action: "pullApartmentPhotos",
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
        data     : {action: "pullApartmentAmenities",
                    id: gid},
        success  : function(data) {
            if(handleResult(data)) {
                $(".amenList").append(data);
                $(".subField").bind("input propertychange", function() { apartmentChange(); });
            }
        }
    });
}

function setApartmentButtons(element) {
    if($("#apartmentRemove").length == 0) {
        $(".apartment-list").append("<div class=\"floating-move-button remove \" id=\"apartmentRemove\" onclick=\"promptConfirm('removeSelectedApartment()', 'Are you sure you want to remove this entry from the apartment list?')\"></div>");
    }

    $("#apartmentRemove").css("left", element.position().left + - $("#apartmentRemove").width() - 2);
    $("#apartmentRemove").css("top", element.position().top + ($("#apartmentRemove").height() / 2) + 1);
}

function apartmentChange() {
    anythingChanged = false;

    if($("#apartmentName").val() != $("#apartmentName").data("old")) anythingChanged = true;
    if($("#apartmentTag").val() != $("#apartmentTag").data("old")) anythingChanged = true;
    if($("#apartmentAddr").val() != $("#apartmentAddr").data("old")) anythingChanged = true;
    if($("#apartmentDesc").val() != $("#apartmentDesc").data("old")) anythingChanged = true;
    if($("#apartmentPrice").val() != $("#apartmentPrice").data("old")) anythingChanged = true;
    if($("#apartmentBed").val() != $("#apartmentBed").data("old")) anythingChanged = true;
    if($("#apartmentBath").val() != $("#apartmentBath").data("old")) anythingChanged = true;
    if($("#apartmentSqft").val() != $("#apartmentSqft").data("old")) anythingChanged = true;
    if($("#apartmentAvail").val() != $("#apartmentAvail").data("old")) anythingChanged = true;
    if($(".amenList").data("changed") == "true") anythingChanged = true;
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