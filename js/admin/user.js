$(function(){
    $(".process-user-file").on('click', function(e){
        e.preventDefault();
        var fileId = $(this).attr('data-id');
        $("#file-process-modal").modal('show');
        $("#file_id").attr('value', fileId);
        return false;
    })  
    
    $("#file-process-confirm-btn").on('click', function(){
        // отправляем запрос скрипту, подтверждающему скан
        sendVerification(1);
    })
    
    $("#file-process-decline-btn").on('click', function(){
        // отправляем запрос скрипту, бракующему скан
        sendVerification(2);
    })
})

function sendVerification(verified)
{
    
        var fileId = $('#file_id').attr('value');
        var reason = $("#file-reason").val();
        if(!fileId) {
            return false;
        }
        
        $.ajax('/admin/user/verifyFile/',
        {
            method:'POST',
            data:{id:fileId, verified:verified, reason:reason},
            success:onFileVerified,
        });
}

function onFileVerified(data, status, xhr)
{
    console.log(data);
    var dataDecoded = JSON.parse(data);
    console.log(dataDecoded);
    //var fileId = parseInt(dataDecoded.fileId);
    var fileId = dataDecoded.fileId;
    
    if(dataDecoded && dataDecoded.code === 0) {
        // если статус изменен
        $("#file-reason").val('');
        $("#file-process-modal").modal('hide');
        $("#file-id-" + fileId).html("<td colspan='4'>Статус файла изменен</td>");
    } else {
        if(dataDecoded && dataDecoded.fileId && dataDecoded.message) {
            $("#file-process-modal").modal('hide');
            $("#file-id-" + fileId).html("<td colspan='4'>" + dataDecoded.message + "</td>");
        }
    }
    
}

// обработка ответа при ajax запросе создания аккаунта в Yurcrm
function onRegisterUserInCRM(data, status, xhr) {
    console.log(status);
    console.log(data.response);
    if(status != 'success') {
        // обработка ошибки при создании аккаунта
        $("#yurcrm-register-result").html('<p class="text-danger">Что-то пошло не так. Не удалось создать аккаунт</p>');
        return;
    }
    var response = JSON.parse(data.response);

    if(parseInt(response.status) == 200) {
        $("#yurcrm-register-result").html('<p class="text-success">Аккаунт создан. Перезагрузите страницу.</p>');
    } else {
        $("#yurcrm-register-result").html('<p class="text-danger">Не удалось создать аккаунт</p>');
    }
}

