function loaded() {
    //nothing
}

function removeSelectedWaitlist() {
    gemail = $(".altSelected").data("email");
    gapt = $(".altSelected").data("apt");

    $.ajax({
        type     : "POST",
        cache    : false,
        url      : "action.php",
        data     : {action: "removeWaitlist",
                    email: gemail,
                    id: gapt},
        success  : function(data) {
            if(handleResult(data)) {
                element = null;

                $(".wait-entry").each(function() {
                    if($(this).data("email") == gemail && $(this).data("apt") == gapt) {
                        element = $(this);
                    }
                });

                element.remove();

                waitlistDeselect();
            }
        }
    });
}

function waitlistDeselect() {
    $(".wait-entry").removeClass("altSelected");

    if($("#waitRemove").length > 0) {
        $("#waitRemove").remove();
    }
}

function waitlistSelect(element) {
    $(".wait-entry").removeClass("altSelected");
    $(element).addClass("altSelected");

    setWaitlistButtons($(element));
}

function setWaitlistButtons(element) {
    if($("#waitRemove").length == 0) {
        $(".wait-list").append("<div class=\"floating-move-button remove larger\" id=\"waitRemove\" onclick=\"promptConfirm('removeSelectedWaitlist()', 'Are you sure you want to remove this entry from the waitlist?')\"></div>");
    }

    $("#waitRemove").css("left", element.position().left + - $("#waitRemove").width() - 2);
    $("#waitRemove").css("top", element.position().top + ($("#waitRemove").height() / 2) + 1);
}