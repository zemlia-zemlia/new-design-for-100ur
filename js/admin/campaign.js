$(function(){
    
    $(".campaign-topup").on('click', function(e){
        e.preventDefault();
        var campaignId = $(this).attr('data-id');
        
        if(!campaignId) {
            return false;
        }
        
        $("#campaign-"+campaignId).show();
        return false;
    })
    
    $(".campaign-topup-close").on('click', function(e) {
        e.preventDefault();
        $(this).closest('form').hide();
        return false;
    })
    
    $(".form-campaign-topup a.submit-topup").on('click', function(e){
        e.preventDefault();
        var form = $(this).closest('form');
        var sum = form.find('[name=sum]').val();
        var campaignId = form.attr('data-id');
        
        $.ajax('/admin/campaign/topup/', {
            method:'POST',
            data:{sum:sum, campaignId:campaignId},
            success:onCampaignTopupSubmit,
        });
        
        form.hide();
        
        //console.log(sum + ': ' + campaignId);
        return false;
    })

})


function onCampaignTopupSubmit(data, textStatus, jqXHR )
{
    console.log('data: '+ data);
    
    var dataDecoded = JSON.parse(data);
    if(dataDecoded && dataDecoded.code == 0) {
        $(".balance-"+dataDecoded.id).text(dataDecoded.balance);
        $(".campaign-topup-message").text('');
    } else {
        $(".campaign-topup-message").hide().text('Не удалось пополнить баланс').show();
    }
    
}
