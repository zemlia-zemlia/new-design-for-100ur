$(function(){
    $(".region-buy-price").on('change', function(){
        var price = $(this).val();
        var regionId = $(this).attr('data-region-id');

        $.ajax('/admin/region/setPrice/', {
            'method':'POST',
            'data': {'id':regionId, 'price': price},
        }).done(function(data){
            
            var jsonData = $.parseJSON(data);
            
            if(jsonData.code == '1') {
                $("[data-region-id=" + jsonData.regionId + "]").closest('div').removeClass('has-error').addClass('has-success');
            } else {
                $("[data-region-id=" + jsonData.regionId + "]").closest('div').removeClass('has-success').addClass('has-error');
            }
        });
    })

    $(".region-capital-buy-price").on('change', function(){
        var price = $(this).val();
        var capitalId = $(this).attr('data-town-id');

        $.ajax('/admin/town/setPrice/', {
            'method':'POST',
            'data': {'id':capitalId, 'price': price},
        }).done(function(data){

            var jsonData = $.parseJSON(data);

            if(jsonData.code == '1') {
                $("[data-town-id=" + jsonData.townId + "]").closest('div').removeClass('has-error').addClass('has-success');
            } else {
                $("[data-town-id=" + jsonData.townId + "]").closest('div').removeClass('has-success').addClass('has-error');
            }
        });
    })
})
