$(function () {
    $(".delete-attachment-link").on('click', function (e) {
        e.preventDefault();
        var fileId = $(this).attr('data-id');
        $.ajax('/admin/file/delete/', {
            method: "POST",
            data: {'id': fileId}
        }).done(function (data) {
            if (data == '0') {
                alert('Не удалось удалить файл');
            } else {
                $('.delete-attachment-link[data-id=' + data + ']').remove();
            }
        })
    })
})