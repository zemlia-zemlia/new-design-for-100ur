$(function(){
    $(".region-buy-price").on('change', function(){
        var price = $(this).val();
        var regionId = $(this).attr('data-id');
        
//        console.log(price);
        
        $.ajax('/admin/region/setPrice/', {
            'method':'POST',
            'data': {'id':regionId, 'price': price},
        }).done(function(data){
            
            var jsonData = $.parseJSON(data);
            
            if(jsonData.code == '1') {
                $("[data-id=" + jsonData.regionId + "]").closest('div').removeClass('has-error').addClass('has-success');
            } else {
                $("[data-id=" + jsonData.regionId + "]").closest('div').removeClass('has-success').addClass('has-error');
            }
        });
    })
})
