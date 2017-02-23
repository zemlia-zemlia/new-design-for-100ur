<?php
    $this->setPageTitle("Контакты юридических центров. ". Yii::app()->name);
    
    Yii::app()->clientScript->registerScriptFile("http://api-maps.yandex.ru/2.0-stable/?load=package.standard&lang=ru-RU",CClientScript::POS_HEAD);
?>




	<h1 class="header-block header-block-light-grey">Адреса филиалов</h1>
	<br/>
	<p>Портал "100 Юристов" предоставляет юридические консультации онлайн для всех жителей РФ, Беларуси и Украины. Нас егодняшний день вы можете получить очную консультацию, консультацию по телефону в следующих филиалах:</p>
	<br/>
					<h5>Москва</h5>
                    <div class="vcard">
                    <div>
                      <span class="category">Юридический центр Москва</span>
                      <span class="fn org">100 Юристов</span>
                    </div>
                    <div class="adr">
                      <span class="locality">г. Москва</span>,
                      <span class="street-address">Шлюзовая набережная, д.6, стр. 4</span>
                    </div>
                    <div>Мы работаем <span class="workhours">ежедневно с 10:00 до 19:00</span>
                      <span class="url">
                        <span class="value-title" title="http://www.100yuristov.com"> </span>
                      </span>
                    </div>
                   </div>
<hr />
					<h5>Санкт-Петербург</h5>
                    <div class="vcard">
                    <div>
                      <span class="category">Юридический центр Санкт-Петербург</span>
                      <span class="fn org">100 Юристов</span>
                    </div>
                    <div class="adr">
                      <span class="locality">г. Санкт-Петербург</span>,
                      <span class="street-address">ул. 40 лет Победы, 8</span>
                    </div>
                    <div>Мы работаем <span class="workhours">ежедневно с 00:00 до 24:00</span>
                      <span class="url">
                        <span class="value-title" title="http://www.100yuristov.com"> </span>
                      </span>
                    </div>
                   </div>
<hr />
					<h5>Нижний Новгород</h5>
                    <div class="vcard vert-margin30">
                    <div>
                      <span class="category">Юридический центр Нижний Новгород</span>
                      <span class="fn org">100 Юристов</span>
                    </div>
                    <div class="adr">
                      <span class="locality">г. Нижний Новгород</span>,
                      <span class="street-address">ул. Новая д.28</span>
                    </div>
                    <div>Мы работаем <span class="workhours">ежедневно с 00:00 до 24:00</span>
                      <span class="url">
                        <span class="value-title" title="http://www.100yuristov.com"> </span>
                      </span>
                    </div>
                   </div>
    
              
                    <div class="flat-panel">
                        <div class="inside">
                            <h2>Форма обратной связи</h2>                                        
                            <?php $this->renderPartial('_contactForm', array('model'=>$contactForm));?>
                        </div>
                    </div>                                        


<?php if(sizeof($formResult)):?>
<!-- Modal -->
<div class="modal fade" id="contact-form-result" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">
            <?php
                if(isset($formResult['code']) && $formResult['code'] === 0) {
                    echo "Сообщение отправлено";
                } else {
                    echo "Сообщение не отправлено";
                }
            ?>
        </h4>
      </div>
      <div class="modal-body">
        <?php
            echo $formResult['message'];
        ?>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
    $(function(){
        $("#contact-form-result").modal("show");
        
        $("#contact-form-result").on('hidden.bs.modal', function (e) {
            location.href = location.href;
        });
    })
</script>
<?php endif; ?>

<?php if($contactForm):?>
<script>
    $(function(){
        scrollToElement("#contact-form");
    })
</script>
<?php endif; ?>


