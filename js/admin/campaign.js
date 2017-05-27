$(function(){
    
    $(".buyer-topup").on('click', function(e){
        e.preventDefault();
        var campaignId = $(this).attr('data-id');
        
        if(!campaignId) {
            return false;
        }
        
        $("#buyer-"+campaignId).show();
        return false;
    })
    
    $(".buyer-topup-close").on('click', function(e) {
        e.preventDefault();
        $(this).closest('form').hide();
        return false;
    })
    
    $(".form-buyer-topup a.submit-topup").on('click', function(e){
        e.preventDefault();
        var form = $(this).closest('form');
        var sum = form.find('[name=sum]').val();
        var buyerId = form.attr('data-id');
        
        $.ajax('/admin/campaign/topup/', {
            method:'POST',
            data:{sum:sum, buyerId:buyerId},
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
        $(".buyer-topup-message").text('');
    } else {
        $(".buyer-topup-message").hide().text('Не удалось пополнить баланс').show();
    }
    
}
