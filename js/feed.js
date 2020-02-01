$(function(){
    $(document).on('click', ".hide-comment", function(e){
        e.preventDefault();
        var commentObject = $(this);
        var questionId = commentObject.attr('data-id');
        //console.log(questionId);
        
        $.ajax('/question/checkCommentsAsRead/', {
            method:'POST',
            data:{id:questionId},
        }).done(function(jsonData){
            data = $.parseJSON(jsonData);
            //console.log(data.code);
            //console.log(commentObject);
            if(data.code == '200') {
                //console.log('ok!');
                commentObject.closest('.feed-item').fadeOut(600);
            }
        });
        
    });
})    
    