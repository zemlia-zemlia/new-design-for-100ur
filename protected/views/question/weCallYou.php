<?php
$this->setPageTitle('Звонок юриста заказан' . Yii::app()->name);
?>
<div class='panel panel-default'>
    <div class='panel-body'>
        <h1>Юрист перезвонит Вам в ближайшее время</h1>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="thank-you-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Спасибо!</h4>
      </div>
      <div class="modal-body">
        <h2 class="vert-margin30">Юрист перезвонит Вам в ближайшее время</h2>
        
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