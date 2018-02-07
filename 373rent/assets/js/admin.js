backgroundLocked = false;

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
        return false;
    }

    if(result == "error") {
        return false;
    }

    if(result == "invalidaction") {
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
    $("body").append("<div class=\"confirm-box\"><p>Are you sure you want to " + prompt + "?</p><button class=\"green-button\" onclick=\"cancelConfirm();" + targetFunction + "\">Yes</button><button class=\"red-button\" onclick=\"cancelConfirm()\">No</button></div>");
}

