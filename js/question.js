function onSpamQuestion(data, textStatus, jqXHR)
{
    var jsonData = JSON.parse(data);
    var status = jsonData.status;
    if(status == 1){
        var questionId = jsonData.id;
        $("tr#question-" + questionId).fadeOut(500);
    } else {
        alert('Ошибка: не удалось изменить статус вопроса');
    }
}


$(function(){
    $("#Question_categories input[type=checkbox]").on('change', function(){
        var checkedCategories = $(this).closest("#Question_categories").find("input[type=checkbox]:checked").length;
        if(checkedCategories == 3) {
            $(this).closest("#Question_categories").find("input[type=checkbox]").each(function(){
                if(!$(this).prop("checked")) {
                    $(this).prop("disabled", true);
                }
            })
        } else {
            $(this).closest("#Question_categories").find("input[type=checkbox]").prop("disabled", false);
        }
        console.log(checkedCategories);
    })
})
