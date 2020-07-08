

$(function () {
    var robotWidgetCookie = get_cookie('hideRobotwidget');
    console.log(robotWidgetCookie);
    if (robotWidgetCookie != 1) {
        // автоматически показываем чат через 10 секунд после загрузки страницы,
        // если пользователь перед этим не закрывал виджет принудительно
        // setTimeout(slideRobotChat, 10000);
    }

})

var obj = [
    {step1: {item: "Здравствуйте! Я юрист-консультант сайта. Чем я могу вам помочь?"}},
    {step2: {item: "Моя консультация бесплатна. Какой у Вас вопрос?"}},
    {
        step3: {
            item: 'Если вам сложно сформулировать вопрос вы можете ' +
            ' <a class="" href="' + robotWidgetQuestionUrl + '">Заказать обратный звонок. </a> Звонок для вас бесплатный. <br/>'
        }
    }
];

function slideRobotChat() {
    $("#robot_chat__contentMess").html(""),
        $("#robot_chat__contentMess").empty(),
        !0 === $("#robot_chat").hasClass("show") ? ($("#robot_chat").removeClass("show"),
            $("#robot_chat__wrap").hide(), $("#robot_chat__header1 .addq__small-info-bl1").show(),
            $("#robot_chat__header1 .robot_chat__header__close").hide(),
            document.cookie = "hideRobotwidget=1",
            addMess(!0)) : ($("#robot_chat").addClass("show"),
            $("#robot_chat__wrap").show(),
            $("#question_komm_bottom").focus(),
            $("#robot_chat__header1 .addq__small-info-bl1").hide(),
            $("#robot_chat__header1 .robot_chat__header__close").show(),
            $("#robot_chat_printed").show(), setTimeout(function () {
            addMess()
        }, 1500))
}

function addMess(s) {
    var a = 0;
    $("#robot_chat__contentMess").empty(), f = function () {
        if (s) return stopAllTimeouts(), !1;
        var t = obj[a]["step" + parseInt(a + 1)].time = robotGetTime(), o = obj[a]["step" + parseInt(a + 1)].item;
        $("#robot_chat_printed").hide();
        var e = '<div class="robot_chat_item"><div class="robot_chat_item__content">' + o + '</div><div class="robot_chat_item__date">' + t + "</div></div>";
        $("#robot_chat__contentMess").append(e), (a += 1) < 3 && (setTimeout(f, "3000"), $("#robot_chat_printed").fadeIn())
    }, f()
}

function robotGetTime() {
    var t = new Date, o = t.getHours(), e = t.getMinutes();
    return o < 10 && (o = "0" + o.toLocaleString()), e < 10 && (e = "0" + e.toLocaleString()), o + ":" + e
}
