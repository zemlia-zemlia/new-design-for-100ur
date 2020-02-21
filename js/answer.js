function onSpamAnswer(data, textStatus, jqXHR)
{
    var jsonData = JSON.parse(data);
    var status = jsonData.status;
    console.log(jsonData);
    if(status == 1){
        var answerId = jsonData.id;
        $("#answer-" + answerId).html('<div class="alert alert-success">Отправлен в спам</div>');
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
        $("#answer-" + answerId).html('<div class="alert alert-success">Одобрен</div>');
    } else {
        alert('Ошибка: не удалось изменить статус ответа');
    }
}

function onPayBonus(data, textStatus, jqXHR)
{
    var jsonData = JSON.parse(data);
    var status = jsonData.status;
    if(status == 1){
        var answerId = jsonData.id;
        $("#answer-" + answerId).html('<div class="alert alert-success">Одобрен и оплачен</div>');
    } else {
        alert('Ошибка: не удалось изменить статус ответа и оплатить его');
    }
}
