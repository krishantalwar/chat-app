require("./bootstrap");

/**
 * jQuery
 */
window.$ = window.jQuery = require("jquery");

let userId = document.head.querySelector("meta[name='pusher-id']").content;

var CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");

Echo.private("App.Models.User." + userId).notification((notification) => {
    console.log(notification);

    if ($(".userlist").hasClass("activeuser")) {
        $.ajax({
            url: "/get_user_chat",
            type: "POST",
            data: {
                _token: CSRF_TOKEN,
                user_id: $(".activeuser").attr("id"),
            },
            dataType: "JSON",
            success: function (data) {
                if (data.type == "success") {
                    var message = data.message;
                    var d = $("." + notification.chat.chat_id);
                    d.html(data.document);
                    // window.show_msg_notification();
                    // window.real_time_chat_get();
                } else {
                }
            },
        });
    }
});
