function loaded() {
    //nothing yet
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

    if($("#neighborhoodRemove").length > 0) {
        $("#neighborhoodRemove").remove();
    }
}

function neighborhoodSelect(element) {
    $(".neighborhood-entry").removeClass("altSelected");
    $(element).addClass("altSelected");

    setNeighborhoodButtons($(element));
}

function setNeighborhoodButtons(element) {
    if($("#neighborhoodRemove").length == 0) {
        $(".neighborhood-list").append("<div class=\"floating-move-button remove \" id=\"neighborhoodRemove\" onclick=\"promptConfirm('removeSelectedNeighborhood()', 'Are you sure you want to remove this entry from the things to do?')\"></div>");
    }

    $("#neighborhoodRemove").css("left", element.position().left + - $("#neighborhoodRemove").width() - 2);
    $("#neighborhoodRemove").css("top", element.position().top + ($("#neighborhoodRemove").height() / 2) + 1);
}