$(function(){
    $(".set-category-link").on('click', function(e){
        e.preventDefault();
        var catId = $(this).attr('data-category');
        var questionId = $(this).attr('data-question');
        
        console.log(catId);
        console.log(questionId);
        
        $.ajax('/admin/question/setCategory/',
        {
            data:{catId:catId, questionId:questionId},
            method:'POST',
            success:onCategorySet
        });
        
        return false;
    })
})


function onCategorySet(data, requestStatus, xhr)
{
    console.log(data);
    var jsonData = JSON.parse(data);
    var status = jsonData.status;
    var questionId = jsonData.questionId;
    
    if(status === 0){
        $("tr#question-" + questionId).fadeOut(500);
    } else {
        $("tr#question-" + questionId).text('Ошибка: не удалось изменить категорию вопроса');
    }
}

function onSpamSingleQuestion(data, requestStatus, xhr)
{
    var jsonData = JSON.parse(data);
    var status = jsonData.status;
    if(status == 1){
        var questionId = jsonData.id;
        document.cookie = "'lastModeratedQuestionId'=" + questionId;
        location.href = '/admin/question/setTitle';
    } else {
        alert('Ошибка: не удалось изменить статус вопроса');
    }
}