function onSpamComment(data, textStatus, jqXHR)
{
    var jsonData = JSON.parse(data);
    var status = jsonData.status;
    if(status == 1){
        var answerId = jsonData.id;
        $("tr#comment-" + answerId).html('<td colspan="3"><div class="alert alert-success">Отправлен в спам</div></td>');
    } else {
        alert('Ошибка: не удалось изменить статус отзыва');
    }
}


function onPublishComment(data, textStatus, jqXHR)
{
    var jsonData = JSON.parse(data);
    var status = jsonData.status;
    if(status == 1){
        var answerId = jsonData.id;
        $("tr#comment-" + answerId).html('<td colspan="3"><div class="alert alert-success">Одобрен</div></td>');
    } else {
        alert('Ошибка: не удалось изменить статус отзыва');
    }
}
