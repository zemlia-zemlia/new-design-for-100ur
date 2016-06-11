<?php
$this->setPageTitle("Вопрос отправлен юристу." . Yii::app()->name);

?>

<!-- Modal -->
<div class="modal fade" id="thank-you-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Вопрос отправлен юристу</h4>
      </div>
      <div class="modal-body">
        <p>
            Ваш вопрос отправлен. Вы получите ответ на него в ближайшее время.
        </p>
        <p>
            <strong>Спасибо за Ваш вопрос!</strong>
        </p>
        <p class="center-align">
            <a href="/" class="btn btn-primary">На главную страницу</a>
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
