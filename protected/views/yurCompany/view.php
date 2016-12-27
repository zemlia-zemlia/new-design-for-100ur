<?php
    $this->setPageTitle($company->name . ' ' . CHtml::encode($company->town->name) . '. Отзывы, контакты');

    Yii::app()->clientScript->registerMetaTag(CHtml::encode($company->name . ' ' . $company->town->name . ', адрес, отзывы'), "Description");

        
    $this->breadcrumbs=array(
        'Юридические фирмы' =>  array('/company'),
        CHtml::encode($company->town->name) =>  array('yurCompany/town', 'alias'=>$company->town->alias),
        $company->name,
    );
    
?>

<?php
    $this->widget('zii.widgets.CBreadcrumbs', array(
        'homeLink'=>CHtml::link('Вопрос юристу',"/"),
        'separator'=>' / ',
        'links'=>$this->breadcrumbs,
     ));
?>

    <div itemscope="" itemtype="http://schema.org/Organization">
    <h1 class="vert-margin30"><span itemprop="name"><?php echo CHtml::encode($company->name); ?></span></h1>
        
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4">
                    <?php if($company->logo):?>
                    <?php echo CHtml::image($company->getPhotoUrl(), CHtml::encode($company->name), array('class'=>'img-responsive'));?>
                    <?php endif;?>
                </div>
                <div class="col-md-8">
                    <?php if($company->town):?>
                    <p><strong>Город:</strong> <?php echo CHtml::encode($company->town->name);?></p>
                    <?php endif;?>
                    
                    <?php if($company->address):?>
                    <p itemprop="address"><strong>Адрес:</strong> <?php echo CHtml::encode($company->address);?></p>
                    <?php endif;?>
                    
                    <?php if($company->metro):?>
                    <p><strong>Метро:</strong> <?php echo CHtml::encode($company->metro);?></p>
                    <?php endif;?>
                    
                    <?php if($company->yearFound):?>
                    <p><strong>Год основания:</strong> <?php echo CHtml::encode($company->yearFound);?></p>
                    <?php endif;?>
                    
                    <?php if($company->website):?>
                    <p><strong>Сайт:</strong> <?php echo CHtml::encode($company->website);?></p>
                    <?php endif;?>
                    
                    <p><strong>Телефон:</strong> 
                    <?php for($p=1;$p<=3;$p++){
                        $phoneField = 'phone' . $p;
                        if($company->{$phoneField}) {
                            echo '<span itemprop="telephone">' . $company->{$phoneField} . '</span>&nbsp;&nbsp;';
                        }
                    }
                    ?>
                    </p>
                    
                    <?php if($company->yurName):?>
                    <p><strong>Юридическое название:</strong> <?php echo CHtml::encode($company->yurName);?></p>
                    <?php endif;?>
                    
                    <?php if($company->yurAddress):?>
                    <p><strong>Юридический адрес:</strong> <?php echo CHtml::encode($company->yurAddress);?></p>
                    <?php endif;?>
                </div>
            </div>
        </div>
        
    <span itemprop="description">
        <?php echo $company->description;?>
    </span>

<?php if($company->commentsChecked):?>

        <h2>Отзывы</h2>
        <?php foreach($company->commentsChecked as $com):?>
        <div itemprop="review" itemscope itemtype="http://schema.org/Review">  
            
            <div itemprop="itemReviewed" itemscope="" itemtype="http://schema.org/Organization" style="display: none;">
                <meta itemprop="name" content="<?php echo CHtml::encode($company->name); ?>">
                <meta itemprop="address" content="<?php echo CHtml::encode($company->address);?>">
                <?php for($p=1;$p<=3;$p++){
                        $phoneField = 'phone' . $p;
                        if($company->{$phoneField}) {
                            echo '<span itemprop="telephone">' . $company->{$phoneField} . '</span>&nbsp;&nbsp;';
                        }
                    }
                    ?>
            </div>
            
            <div class="review-item row">
                <div class="col-sm-3">
                    <?php if($com->author):?>
                        <img src="<?php echo $com->author->getAvatarUrl();?>" /><br />
                        <small><span itemprop="author"><?php echo CHtml::encode($com->author->name);?></span></small>
                    <?php elseif($com->authorName):?>
                        <small>
                            <span itemprop="author"><?php echo CHtml::encode($com->authorName);?></span>
                        </small>    
                    <?php endif;?>
                        <p>
                            <small>
                                <?php echo CustomFuncs::niceDate($com->dateTime, false);?>
                            </small>
                            <span itemprop="datePublished" style="display:none;"><?php echo date("c", strtotime($com->dateTime));?></span>
                            <?php echo CHtml::link("", Yii::app()->createUrl('yurCompany/view', array('id'=>$company->id)), array('itemprop'=>"url", 'style'=>'display:none;' ));?>
                        </p>    
                </div>
                <div class="col-sm-9">
                    <p><span itemprop="reviewBody"><?php echo CHtml::encode($com->text);?></span></p>
                    <?php if($com->rating):?>
                    <p><strong>Оценка:</strong> 
                        <span itemprop="reviewRating"><?php echo (int)$com->rating;?></span>/5</p>
                    <?php endif;?>
                </div>
            </div>
        </div>
        <?php endforeach;?>


<?php endif;?>

</div>  <!-- Product --> 


<div class="panel panel-default">
    <div class="panel-body">
        <h2>Оставьте свой отзыв</h2>
        <?php $this->renderPartial("application.views.comment._form", array('model'=>$comment));?>
    </div>
</div>



<?php if($commentSaved === true):?>
<script>
    $(function(){
        $('#comment-saved-modal').modal('show');
    })
</script>

<div class="modal fade" id="comment-saved-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Отзыв сохранен</h4>
      </div>
      <div class="modal-body">
          <p>Ваш отзыв сохранен. Он появится на сайте после проверки модератором.</p>
      </div>
    </div>
  </div>
</div>

<?php endif;?>