$(function(){
    $(document).on('click', ".hide-comment", function(e){
        e.preventDefault();
        var commentObject = $(this);
        var questionId = commentObject.attr('data-id');

        $.ajax('/question/checkCommentsAsRead/', {
            method:'POST',
            data:{id:questionId},
        }).done(function(jsonData){
            data = $.parseJSON(jsonData);

            if(data.code == '200') {
                commentObject.closest('.feed-item').fadeOut(600);
            }
        });
        
    });
})    
    