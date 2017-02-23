$(function(){
    $(".lead-change-status").on('click', function(e){
        e.preventDefault();
        var leadId = $(this).attr('data-id');
        var newStatus = $(this).attr('data-status');
        
        //console.log(leadId + ': ' + newStatus);
        
        $.ajax('/admin/lead/changeStatus/',
            {
                method:'POST',
                data:{id:leadId,status:newStatus},
                success:onChangeLeadStatus,
            }
        );
        
        return false;
    })
})

function onChangeLeadStatus(data, status, xhr)
{
    console.log('data: '+ data);
    
    var dataDecoded = JSON.parse(data);
    if(dataDecoded && dataDecoded.code == 0) {
        $('#lead-'+dataDecoded.id).html('<td colspan="2">Статус изменен</td>');
    } else {
        $("#lead-status-message-"+dataDecoded.id).hide().text('Не удалось изменить статус. Описание ошибки: '+dataDecoded.message).show();
    }
}


