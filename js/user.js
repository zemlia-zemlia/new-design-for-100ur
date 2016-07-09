$(function(){
    toggleUserForm();
    
    $(".radio-labels input[type=radio]").change(function(){
        toggleUserForm();
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

