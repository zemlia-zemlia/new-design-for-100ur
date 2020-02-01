<div class="new-lead-wrapper vert-margin30">
<?php
    Yii::app()->controller->renderPartial('application.modules.admin.views.lead._form', array('model'=>$model, 'action'=>'/admin/lead/create/'));
?>
    
    
</div>

<div id="new-lead-message"></div>

<script type="text/javascript">
    $(function(){
        $('.new-lead-wrapper .new-lead-form input[type=submit]').on('click', function(e){
            e.preventDefault();
            $('#new-lead-message').text('Отправка лида...').show();
            var form = $(this).closest('form');
            var formAction = form.attr('action');
            var formData = form.serialize();
            console.log(formAction);
            console.log(formData);
            $.ajax(formAction, {
                data:formData,
                method:'POST',
                success:onCreateLead,
            });
            return false;
        })
    })
    
    function onCreateLead(data, status, xhr)
    {
        console.log(data);
        if(data == 'ok') {
            $('.new-lead-wrapper #lead-form')[0].reset();
            $('#new-lead-message').text('Лид сохранен').show();
        } else {
            $('#new-lead-message').text('Ошибка, лид не сохранен').show();
        }
    }
</script>

