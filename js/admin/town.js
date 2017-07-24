$(function(){
    $(".town-buy-price").on('change', function(){
        var price = $(this).val();
        var townId = $(this).attr('data-id');
        
        console.log(price);
        
        $.ajax('/admin/town/setPrice/', {
            'method':'POST',
            'data': {'id':townId, 'price': price},
        }).done(function(data){
            
            var jsonData = $.parseJSON(data);
            
            if(jsonData.code == '1') {
                $("[data-id=" + jsonData.townId + "]").closest('div').addClass('has-success');
            } else {
                $("[data-id=" + jsonData.townId + "]").closest('div').addClass('has-error');
            }
        });
    })
})
