$(function(){
    $('.change-direction-parent').on('change', function(e){
        e.preventDefault();
        console.log('Setting new parent');
        var directionId = $(this).attr('data-id');
        var parentId = $(this).val();
        var currentField = $(this);
        
        $.ajax('/admin/questionCategory/setDirectionParent/', {
            data:{id:directionId, parentId:parentId},
            method:'POST',
        }).done(function(data){
            var dataDecoded = JSON.parse(data);
            if(dataDecoded.message) {
                currentField.closest('tr').find('.set-parent-result').text(dataDecoded.message);
            }
        });
        return false;
    })
});