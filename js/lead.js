// функции для работы с контактами

function LeadToQuestionAjax(data, textStatus, jqXHR)
{
    if(data) {
        $("p#lead_" + data).html('Лид экспортирован в вопрос').addClass("bg-success");
    } else {
        $("p#lead_" + data).html('<span class="label label-danger">Ошибка при экспорте контакта</span>');
    }
}