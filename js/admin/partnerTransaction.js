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
       
       changeRequestStatus(requestId, newStatus, '');
 
       return false;
    })
});

function changeRequestStatus(requestId, newStatus, requestComment)
{
    $.ajax('/admin/partnerTransaction/change/',
        {
            data:{id:requestId, status:newStatus},
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
            $("#request-id-" + requestId).html("<td colspan='5'>Запрос обработан</td>");
        }
    }

}