function loaded() {
    $(".cover-caption").bind("input propertychange", function() { coverChange(); });
    $(".cover-caption").on('keyup', function (e) {
        if (e.keyCode == 13 && !backgroundLocked) {
            if(!$(".cover-button").attr("disabled")) {
                coverUpdate();
            }
        }
    });
}

function gotChange(fileBox) {

    if(fileBox.value != "") {
        formdata = new FormData();
        file = fileBox.files[0];
        adjustedName = "assets/images/cover/" + file.name;

        formdata.append("image", file);
        formdata.append("action","uploadCover");

        $.ajax({
            type     : "POST",
            cache    : false,
            contentType: false,
            processData: false,
            url      : "action.php",
            data     : formdata,
            success: function(data) {
                fileBox.value = "";
                if(handleResult(data)) {
                    $(".cover-list").append("<div class=\"cover-entry\" onclick=\"coverSelect(this)\" data-caption=\"\">" + adjustedName + "</div>");
                }
            }
        });
    }
}

function moveUpSelected() {
    imgSrc = $(".altSelected").html();

    $.ajax({
        type     : "POST",
        cache    : false,
        url      : "action.php",
        data     : {action: "moveUpCover",
                    src: imgSrc},
        success  : function(data) {
            if(handleResult(data)) {
                element = null;

                $(".cover-entry").each(function() {
                    if($(this).html() == imgSrc) {
                        element = $(this);
                    }
                });

                rightBefore = null;
                foundCover = false;
                $(".cover-entry").each(function(index, elem) {
                    if(foundCover) return;
                    if($(elem).is(element)) {
                        foundCover = true;
                        return;
                    }
                    rightBefore = $(elem);
                });

                if(rightBefore == null) {
                    return;
                }

                element.insertBefore(rightBefore);
                setCoverButtons(element);
            }
        }
    });
}

function moveDownSelected() {
    imgSrc = $(".altSelected").html();

    $.ajax({
        type     : "POST",
        cache    : false,
        url      : "action.php",
        data     : {action: "moveDownCover",
                    src: imgSrc},
        success  : function(data) {
            if(handleResult(data)) {
                element = null;

                $(".cover-entry").each(function() {
                    if($(this).html() == imgSrc) {
                        element = $(this);
                    }
                });

                rightAfter = null;
                foundCover = false;
                $(".cover-entry").each(function(index, elem) {
                    if($(elem).is(element)) {
                        foundCover = true;
                        return;
                    }
                    if(!foundCover) return;
                    if(rightAfter == null) {
                        rightAfter = $(elem);
                    }
                });

                if(rightAfter == null) {
                    return;
                }

                element.insertAfter(rightAfter);
                setCoverButtons(element);
            }
        }
    });
}

function removeSelectedCover() {
    imgSrc = $(".altSelected").html();

    $.ajax({
        type     : "POST",
        cache    : false,
        url      : "action.php",
        data     : {action: "removeCover",
                    src: imgSrc},
        success  : function(data) {
            if(handleResult(data)) {
                element = null;

                $(".cover-entry").each(function() {
                    if($(this).html() == imgSrc) {
                        element = $(this);
                    }
                });

                element.remove();

                coverDeselect();
            }
        }
    });
}

function coverUpdate() {
    imgSrc = $(".altSelected").html();
    imgCap = $(".cover-caption").val();

    $.ajax({
        type     : "POST",
        cache    : false,
        url      : "action.php",
        data     : {action: "updateCoverCaption",
                    src: imgSrc,
                    caption: imgCap},
        success  : function(data) {
            if(handleResult(data)) {
                element = null;

                $(".cover-entry").each(function() {
                    if($(this).html() == imgSrc) {
                        element = $(this);
                    }
                });

                element.data("caption", imgCap);
                coverDeselect();
                coverSelect(element);
            }
        }
    });
}

function coverDeselect() {
    $(".cover-entry").removeClass("altSelected");

    if($("#coverUpArrow").length > 0) {
        $("#coverUpArrow").remove();
    }
    if($("#coverDownArrow").length > 0) {
        $("#coverDownArrow").remove();
    }
    if($("#coverRemove").length > 0) {
        $("#coverRemove").remove();
    }

    $(".cover-preview").html("Select a cover image from the left");
    $(".cover-caption").data("old", "");
    $(".cover-caption").val("");
    $(".cover-caption").attr("disabled", "disabled");
    $(".cover-button").attr("disabled", "disabled");
    $(".cover-button").addClass("disabledButton");
}

function coverSelect(element) {
    if($(element).hasClass("altSelected")) return;
    $(".cover-entry").removeClass("altSelected");
    $(element).addClass("altSelected");
    imgSrc = $(element).html();
    imgCap = $(element).data("caption");

    $(".cover-preview").html("<img src=\"" + imgSrc + "\" style=\"width:100%;height:100%\" />");
    $(".cover-caption").val(imgCap);
    $(".cover-caption").data("old", imgCap);
    $(".cover-caption").removeAttr("disabled");
    $(".cover-button").attr("disabled", "disabled");
    $(".cover-button").addClass("disabledButton");

    setCoverButtons($(element));
}

function setCoverButtons(element) {
    if($("#coverUpArrow").length == 0) {
        $(".cover-list").append("<div class=\"floating-move-button uparrow\" id=\"coverUpArrow\" onclick=\"moveUpSelected()\"></div>");
    }
    if($("#coverDownArrow").length == 0) {
        $(".cover-list").append("<div class=\"floating-move-button downarrow\" id=\"coverDownArrow\" onclick=\"moveDownSelected()\"></div>");
    }
    if($("#coverRemove").length == 0) {
        $(".cover-list").append("<div class=\"floating-move-button remove\" id=\"coverRemove\" onclick=\"promptConfirm('removeSelectedCover()', 'Are you sure you want to remove this cover?')\"></div>");
    }

    $("#coverUpArrow").css("left", element.position().left + element.width() + $("#coverUpArrow").width());
    $("#coverUpArrow").css("top", element.position().top);
    $("#coverDownArrow").css("left", element.position().left + element.width() + $("#coverDownArrow").width());
    $("#coverDownArrow").css("top", element.position().top + $("#coverDownArrow").height() + 1);
    $("#coverRemove").css("left", element.position().left + - $("#coverRemove").width() - 2);
    $("#coverRemove").css("top", element.position().top + ($("#coverRemove").height() / 2) + 1);
}

function coverChange() {
    if($(".cover-caption").val() == $(".cover-caption").data("old")) {
        $(".cover-button").attr("disabled", "disabled");
        $(".cover-button").addClass("disabledButton");
    } else {
        $(".cover-button").removeAttr("disabled");
        $(".cover-button").removeClass("disabledButton");
    }
}