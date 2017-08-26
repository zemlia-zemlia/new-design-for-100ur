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
        var account = form.find('[name=account]').val();
        var buyerId = form.attr('data-id');
        
        $.ajax('/admin/campaign/topup/', {
            method:'POST',
            data:{sum:sum, account:account, buyerId:buyerId},
            success:onCampaignTopupSubmit,
        });
        
        form.hide();
        
        //console.log(sum + ': ' + campaignId);
        return false;
    })
    
    $(document).on('change', '.set-real-limit', function(){
        var campaignId = $(this).attr('data-id');
        var limit = $(this).val();
        var currentField = $(this);
        
        $.ajax('/admin/campaign/setLimit/', {
            'data' : {'id':campaignId, 'limit': limit},
            'method':'POST',
        }).done(function(data){
            console.log(data);
            console.log($(this));
            
            if(data == '1') {
                currentField.closest('div').removeClass('has-danger').addClass('has-success');
            } else {
                currentField.closest('div').removeClass('has-success').addClass('has-danger');
            }            
        });
    });

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
