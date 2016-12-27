// функции для работы с контактами

function LeadToQuestionAjax(data, textStatus, jqXHR)
{
    if(data) {
        $("p#lead_" + data).html('Готово').addClass("bg-success");
    } else {
        $("p#lead_" + data).html('<span class="label label-danger">Ошибка при экспорте контакта</span>');
    }
}


$(function(){
    
    $(".brak-lead").on('click', function(e){
        e.preventDefault();
        var leadId = $(this).attr('data-id');
        
        if(!leadId) {
            return false;
        }
        
        $("#lead-"+leadId).show();
        return false;
    })
    
    $(".submit-brak-close").on('click', function(e) {
        e.preventDefault();
        $(this).closest('form').hide();
        return false;
    })
    
    $(".form-brak-lead a.submit-brak-lead").on('click', function(e){
        e.preventDefault();
        var form = $(this).closest('form');
        var reason = form.find('#Lead100_brakReason').val();
        var leadId = form.attr('data-id');
        
        $.ajax('/cabinet/brakLead/', {
            method:'POST',
            data:{reason:reason, leadId:leadId},
            success:onLeadBrakSubmit,
        });
        
        form.hide();
        
        //console.log(sum + ': ' + campaignId);
        return false;
    })

})


function onLeadBrakSubmit(data, textStatus, jqXHR )
{
    console.log('data: '+ data);
    
    var dataDecoded = JSON.parse(data);
    if(dataDecoded && dataDecoded.code == 0) {
        $(".brak-lead[data-id="+dataDecoded.id+"]").remove();
        $(".brak-lead-message[data-id="+dataDecoded.id+"]").text('лид отправлен на отбраковку').show();
    } else {
        $(".brak-lead-message[data-id="+dataDecoded.id+"]").hide().text('Не удалось отправить лид на отбраковку').show();
    }
    
}