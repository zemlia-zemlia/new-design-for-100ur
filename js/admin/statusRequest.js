/*
 * обработчики для страницы управления заявками на изменение статуса юриста
 */
$(function(){
    $(".change-request-status").on('click', function(e){
       e.preventDefault();
       
       var requestAction = $(this).data('action');
       var requestId = $(this).data('id');
       
       if(requestAction == 'accept') {
           var newStatus = 1; // одобрен
       } else {
           var newStatus = 2; // не одобрен
       }
       
       if(newStatus == 2) {
           $(this).closest(".request-control-wrapper").find(".request-comment-wrapper").show();
       } else {
           changeRequestStatus(requestId, newStatus, '');
       }
       
       //console.log('id: ' + requestId + ', new status: ' + newStatus);
       
       
       return false;
    })
    
    $(".request-decline-button").on('click', function(e){
        var currentForm = $(this).closest("form");
        var requestId = currentForm.find('input[name=id]').val();
        var requestComment = currentForm.find('[name=comment]').val();
        var newStatus = 2;
        
        changeRequestStatus(requestId, newStatus, requestComment);
    })
    
    function changeRequestStatus(requestId, newStatus, requestComment)
    {
        $.ajax('/admin/userStatusRequest/change/',
            {
                data:{id:requestId, status:newStatus, requestComment:requestComment},
                method:'post',
                success:onStatusChange,
            }
        );
    }
    
    function onStatusChange(data, status, xhr)
    {
        var jsonData = $.parseJSON(data);
        var code = jsonData.code;
        var requestId = jsonData.id;
        var message = jsonData.message;

        if(code!=0) {
            if(requestId) {
                $("#request-id-" + requestId + " .request-status-message").text(message);
            } else {
                alert(message);
            }
        } else {
            if(requestId) {
                $("#request-id-" + requestId).html("<td colspan='4'>Запрос обработан</td>");
            }
        }

    }
})




