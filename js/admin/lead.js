$(function(){
    $(".lead-change-status").on('click', function(e){
        e.preventDefault();
        var leadId = $(this).attr('data-id');
        var newStatus = $(this).attr('data-status');
        var refund = $(this).attr('data-refund');

        //console.log(leadId + ': ' + newStatus);
        
        $.ajax('/admin/lead/changeStatus/',
            {
                method:'POST',
                data:{id:leadId, status:newStatus, refund:refund},
                success:onChangeLeadStatus,
            }
        );
        
        return false;
    })
    
    $(".force-sell-lead").on('click', function(e) {
        e.preventDefault();
        var leadId = $(this).attr('data-id');
        var campaignId = $(this).attr('data-campaignid');
        
        $.ajax('/admin/lead/forceSell/',
            {
                method:'POST',
                data:{leadId:leadId,campaignId:campaignId},
            }
        ).done(function(data) {
            var dataDecoded = JSON.parse(data);
            if(dataDecoded && dataDecoded.code == 0) {
                $('#force-sell').html('<div class="panel panel-success">Лид продан</div>');
            } else {
                $("#force-sell").html('<div class="panel panel-danger">Не удалось продать лид</div>');
            }
        });

        return false;
    });
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


