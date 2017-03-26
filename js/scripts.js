
$(function(){
	
        $('[data-toggle="tooltip"]').tooltip()
         
	$("#Question_phone, #User_phone, .phone-mask").mask("+7 (999) 999-9999");
        
        $("#left-menu-switch").on('click', function(){
            $("ul#left-menu").toggle();
        });
        
        $( "#town-selector" ).autocomplete({
            source:'/town/ajaxGetList/',
            select: function(event, ui){
                var townId = ui.item.id;
                var townName = ui.item.value;

                $("#selected-town").attr('value',townId);
                $( "#town-selector" ).attr('value',townName);
            }
        });
        
        $( "#vuz-town-selector" ).autocomplete({
            source:'/town/ajaxGetList/',
            select: function(event, ui){
                var townId = ui.item.id;
                var townName = ui.item.value;

                $("#vuz-selected-town").attr('value',townId);
                $( "#vuz-town-selector" ).attr('value',townName);
            }
        });
                
        $(".link-karma-plus").on("click", function(e){
            e.preventDefault();
            var link = $(this).attr('href');
            var answerId = $(this).attr("data-id");
            $.post(link, {answerId:answerId}, onKarmaPlus);
            return false;
        })
        
        $(".hidden-block-trigger").on("click", function(e){
            e.preventDefault();
            var hiddenBlock = $(this).closest(".hidden-block-container").find(".hidden-block");
            hiddenBlock.show();
            $(this).hide();
            return false;
        })
        
        $(".donate-yurist-link").on('click', function(e){
            e.preventDefault();
            $(this).closest(".answer-karma-string").find(".donate-block").show();
            return false;
        })
                
        if($('.annoying-widget').length){
            // начальный сдвиг надоедливого виджета относительно верха документа
            var annoyingOffsetInitial = $("#left-panel").offset().top;
            var annoyingWidthInitial = $('.annoying-widget').css('width');
            var annoyingHeight = $('.annoying-widget').css('height');
            
            $(document).on('scroll', function(){
                var annoyingWidget = $('.annoying-widget');
                if(!annoyingWidget.length) {
                    return false;
                }

                var annoyingOffset = annoyingWidget.offset().top - $(window).scrollTop();

                if(annoyingOffset <0) {
                    annoyingWidget.addClass('annoying-widget-fixed');
                    annoyingWidget.css('width',annoyingWidthInitial);
                    $("#left-panel").css('padding-top', annoyingHeight);
                }
                if($(window).scrollTop() < annoyingOffsetInitial) {
                    annoyingWidget.removeClass('annoying-widget-fixed');
                    $("#left-panel").css('padding-top', 0);
                }
            })
        }

});

function addLink() {
    var body_element = document.getElementsByTagName('body')[0];
    var selection = window.getSelection();

    // Вы можете изменить текст в этой строчке
    var pagelink = "<p>Источник: <a href='"+document.location.href+"'>"+document.location.href+"</a> Юридические консультации онлайн &copy;</p>";

    var copytext = selection + pagelink;
    var newdiv = document.createElement('div');
    newdiv.style.position = 'absolute';
    newdiv.style.left = '-99999px';
    body_element.appendChild(newdiv);
    newdiv.innerHTML = copytext;
    selection.selectAllChildren(newdiv);
    window.setTimeout( function() {
        body_element.removeChild(newdiv);
    }, 0);
}
document.oncopy = addLink;

function onKarmaPlus(data, status, xhr)
{
    var jsonData = JSON.parse(data);
    var status = jsonData.status;
    if(status == 1){
        var answerId = jsonData.answerId;
        
        var answerKarmaBlock = $("#answer-karma-" + answerId);
        answerKarmaBlock.closest(".answer-karma-string").find(".donate-yurist-link").click();
        
        answerKarmaBlock.html("<img src='/pics/2017/icon_checkmark.png' alt='Есть ответ' /> Полезный ответ");
        yaCounter26550786.reachGoal('plus_answer');
    } else {
        alert('Ошибка: не удалось поставить плюс');
    }
}

// делает плавную прокрутку к элементу
// element - селектор
function scrollToElement(element)
{
    destination = $(element).offset().top;	
    $("html, body").animate( { scrollTop: destination }, 1100 );
    return false;
}