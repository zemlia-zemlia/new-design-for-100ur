$(function(){

$( "#category-selector" ).autocomplete({
            source:'/admin/questionCategory/ajaxGetList/',
            select: function(event, ui){
                $("#selected-categories-message").html("");
                var catId = ui.item.id;
                var catName = ui.item.value;
                
                $("#selected-categories input[type=checkbox]").not(':checked').each(function(){
                    $(this).closest('p').remove();
                    
                });
                
                var selectedCats = new Array();
                $("#selected-categories input[type=checkbox]:checked").each(function(){
                    console.log($(this).attr('value'));
                    selectedCats.push($(this).attr('value'));
                });
                
                console.log(selectedCats);
                
                if(selectedCats.length>2) {
                    $("#selected-categories-message").html("<div class='alert alert-warning'>Можно добавить не более 3 категорий</div>");
                    return;
                }
                
                $("#selected-categories").append("<p><input type='checkbox' name='Question[categories][]' checked value='" + catId + "'>" + catName + "</input></p>");
            }
    });
    
});