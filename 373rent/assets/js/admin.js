backgroundLocked = false;

function loaded() {
    //nothing
}

function maintenanceToggle(element) {
    if($(element).is(":checked")) {
        $.ajax({
            type     : "POST",
            cache    : false,
            url      : "action.php",
            data     : {action: "toggleMM",
                        toggle: "true"},
            success  : function(data) {
                if(!handleResult(data)) {
                    $(element).prop("checked", false);
                }
            }
        });
    } else {
        $.ajax({
            type     : "POST",
            cache    : false,
            url      : "action.php",
            data     : {action: "toggleMM",
                        toggle: "false"},
            success  : function(data) {
                if(!handleResult(data)) {
                    $(element).prop("checked", true);
                }
            }
        });
    }
}

function handleResult(result) {
    if(result == "notloggedin") {
        window.location = "login.html";
        return false;
    }

    if(result == "timeout") {
        window.location = "admin.php";
        return false;
    }

    if(result == "noaction") {
        messageBox("No action was specified.");
        return false;
    }

    if(result == "error") {
        messageBox("An unidentified error occured.");
        return false;
    }

    if(result == "numberformat") {
        messageBox("A number formatting error occured.");
        return false;
    }

    if(result == "fileexists") {
        messageBox("Image already exists.");
        return false;
    }

    if(result == "entryexists") {
        messageBox("Entry already exists.");
        return false;
    }

    if(result == "invalidaction") {
        messageBox("An invalid action was specified.");
        return false;
    }

    if(result == "fileproblem") {
        messageBox("There was a problem with the file being uploaded.");
        return false;
    }

    if(result == "failure") {
        return false;
    }

    return true;
}

function logout() {
    $.ajax({
        type     : "POST",
        cache    : false,
        url      : "admin.php",
        data     : {action: "logout"},
        success  : function(data) {
            window.location = "login.html";
        },
        error: function (xhr, ajaxOptions, thrownError) {
            window.location = "login.html";
        }
    });
}

function cancelConfirm() {
    backgroundLocked = false;
    if($(".background-lock").length > 0) $(".background-lock").remove();
    if($(".confirm-box").length > 0) $(".confirm-box").remove();
}

function promptConfirm(targetFunction, prompt) {
    if(backgroundLocked) {
        return;
    }

    backgroundLocked = true;

    $("body").append("<div class=\"background-lock\" onclick=\"cancelConfirm()\"></div>");
    $("body").append("<div class=\"confirm-box\"><p>" + prompt + "</p><button class=\"green-button\" onclick=\"cancelConfirm();" + targetFunction + "\">Yes</button><button class=\"red-button\" onclick=\"cancelConfirm()\">No</button></div>");
}

function messageBox(prompt) {
    $("body").append("<div class=\"message-box\">" + prompt + "</div>")
    $(".message-box").animate({opacity: 1.0}, 500, function() {
        setTimeout(function(){
            $(".message-box").animate({opacity: 0.0}, 500, function() {
                $(".message-box").remove();
            });
        }, 2000);
    });
}