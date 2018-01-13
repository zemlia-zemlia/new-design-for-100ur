
$(function () {
    $("#contact-form #Contact_phone").mask("+7 (999) 999-9999");
    $("#contact-form #Contact_phone2").mask("+7 (999) 999-9999");

    $(".field-phone").mask("+7 (999) 999-9999");

    $("#town-selector").autocomplete({
        source: '/town/ajaxGetList/',
        select: function (event, ui) {
            var townId = ui.item.id;
            var townName = ui.item.value;

            $("#selected-town").attr('value', townId);
            $("#town-selector").attr('value', townName);
        }
    });
    
    // функция, замеряющая длину строки в момент печатания текста в инпуте
    $('.strlen-count').on('keyup', function(){
        var strlen = $(this).val().length;
        $(this).closest('div').find('.length-counter').text('Длина: ' + strlen + ' знаков');
        console.log(strlen);
    })
});