$(function(){
    
    $(".radio-labels input[type=radio]").change(function(){
        toggleUserForm();
    })
    
    $("#YuristSettings_status").on('change', function(){
        var yuristStatus = $(this).val();
        console.log(yuristStatus);
        $("#user-profile-advocat, #user-profile-yurist, #user-profile-judge").hide();
        
        switch(yuristStatus) {
            case '1':
                $("#user-profile-yurist").show();
                break;
            case '2':
                $("#user-profile-advocat").show();
                break;
            case '3':
                $("#user-profile-judge").show();
                break;
        }
    })
    
    $(".checkbox-root").on('click', function(){
        var ischecked = $(this).prop('checked')
        
        if(ischecked) {
            // отметили родительское направление, надо отметить все дочерние
            $(this).closest(".checkbox-container").find(".checkbox-child").prop('checked', true);
        }
    })
        
})



function toggleUserForm()
{
    var current_role = $("input[name='User[role]']:checked").val();
    if(current_role!=10) {
        $(".yurist-fields").hide();
    } else {
        $(".yurist-fields").show();
    }
}

