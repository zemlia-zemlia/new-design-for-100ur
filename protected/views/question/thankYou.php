<?php
$this->setPageTitle("Вопрос отправлен юристу." . Yii::app()->name);

?>

<!-- Modal -->
<div class="modal fade" id="thank-you-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Спасибо!</h4>
      </div>
      <div class="modal-body">
        <h4>
            Для отправки вопроса юристам необходимо подтвердить свой Email. 
            Ссылка для подтверждения уже у Вас на почте.
        </h4>
        
        <p class="center-align">
            <a href="/" class="btn btn-primary">Хорошо</a>
        </p>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
    $(function(){
        $("#thank-you-modal").modal('show');
        
        $('#thank-you-modal').on('hidden.bs.modal', function (e) {
            location.href = '/';
          })
    });
</script>
