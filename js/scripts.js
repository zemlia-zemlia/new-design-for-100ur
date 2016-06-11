
$(function(){
	
	$("#Question_phone").mask("+7 (999) 999-9999");
        
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
    
});