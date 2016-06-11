function onSpamAnswer(data, textStatus, jqXHR)
{
    var jsonData = JSON.parse(data);
    var status = jsonData.status;
    if(status == 1){
        var answerId = jsonData.id;
        $("tr#answer-" + answerId).html('<td colspan="2"><div class="alert alert-success">Отправлен в спам</div></td>');
    } else {
        alert('Ошибка: не удалось изменить статус ответа');
    }
}


function onPublishAnswer(data, textStatus, jqXHR)
{
    var jsonData = JSON.parse(data);
    var status = jsonData.status;
    if(status == 1){
        var answerId = jsonData.id;
        $("tr#answer-" + answerId).html('<td colspan="2"><div class="alert alert-success">Одобрен</div></td>');
    } else {
        alert('Ошибка: не удалось изменить статус ответа');
    }
}

