window.onload = function () {
    $(".close-profile-notifier").on('click', function () {
        $(this).closest(".alert-dismissible").hide();
        document.cookie = "hide_profile_notifier=yes;path=/";
    })
}

