<?php
    $this->setPageTitle($company->name . ' ' . CHtml::encode($company->town->name) . '. ' . CHtml::encode($company->address) . '. Отзывы, контакты');

    Yii::app()->clientScript->registerMetaTag(CHtml::encode($company->name . ' ' . $company->town->name . ', адрес, отзывы'), "Description");

        
    $this->breadcrumbs=array(
        'Юридические фирмы' =>  array('/company'),
        CHtml::encode($company->town->name) =>  array('yurCompany/town', 'alias'=>$company->town->alias),
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
    <h1 class="vert-margin30 header-block-green"><span itemprop="name"><?php echo CHtml::encode($company->name); ?></span></h1>
	
	<div class="alert alert-info">
	  <i><strong>Внимание!</strong> Портал "100 Юристов" не связан с компаниями и организациями находящимися в данном каталоге и не несет ответственности за их деятельность. Каталог несет исключительно информационный характер. </i>
	</div>  
	
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4">
                    <?php if($company->logo):?>
                    <?php echo CHtml::image($company->getPhotoUrl(), CHtml::encode($company->name), array('class'=>'img-responsive'));?>
                    <?php endif;?>
                    
                    <?php
                        $ratingSum = 0;
                        $commentsWithRating = 0;
                        foreach($company->commentsChecked as $com) {
                                if($com->rating) {
                                        $ratingSum += (int)$com->rating;
                                        $commentsWithRating++;
                                }
                        }
                        $averageRating = ($commentsWithRating>0)?round(($ratingSum/$commentsWithRating), 1):0;
                    ?>
                    
                    <?php if($averageRating):?>
			<div itemprop="aggregateRating"
				itemscope itemtype="http://schema.org/AggregateRating" class="center-align">
			   Средняя оценка 
                           <div>
                                <strong style="font-size:20px;">
                                    <span itemprop="ratingValue"><?php echo $averageRating;?></span>/5
                                </strong>
                           </div>
                           <small class="text-muted">
                           основана на <span itemprop="reviewCount"><?php echo $commentsWithRating;?></span> отзывах
                            </small>
                        </div>
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
    <hr/>    
    <span itemprop="description">
        <?php echo $company->description;?>
    </span>
	<hr/>
 
   
        
        <div class="flat-panel">
            <div class="inside">
            <h2>Оставить отзыв о компании</h2>
            <?php $this->renderPartial("application.views.comment._form", array('model'=>$comment));?>
            </div>
        </div> 
        
    <?php if($company->commentsChecked):?>


	<h2 class="header-block-light-grey">Отзывы о компании:</h2>

    
        <br/>
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
            
			
            <?php 
                switch($com->rating) {
                    case 1:case 2:
                        $reviewClass = 'danger';
                        break;
                    case 4:case 5:
                        $reviewClass = 'success';
                        break;
                    default:
                        $reviewClass = 'default';
                }
            ?>
			
			
            <div class="review-item"  style="margin-left:<?php echo ($com->level>0)?(($com->level - 1)*20):0;?>px; margin-top:<?php echo ($com->level>1)?'-40':0;?>px;">
                
                <div class="panel panel-<?php echo $reviewClass;?>">
                    <div class="panel-heading">
                        <small>
                            <?php echo CustomFuncs::niceDate($com->dateTime, false);?>
                        
                        <?php if($com->author):?>
                            <span itemprop="author"><?php echo CHtml::encode($com->author->name);?></span>
                        <?php elseif($com->authorName):?>
                            <span itemprop="author"><?php echo CHtml::encode($com->authorName);?></span>
                        <?php endif;?>
                        </small>
                        <span itemprop="datePublished" style="display:none;"><?php echo date("c", strtotime($com->dateTime));?></span>
                        <?php echo CHtml::link("", Yii::app()->createUrl('yurCompany/view', array('id'=>$company->id)), array('itemprop'=>"url", 'style'=>'display:none;' ));?>
                    </div>
                    
                    <div class="panel-body">
                        <p><span itemprop="reviewBody"><?php echo CHtml::encode($com->text);?></span></p>
                            <?php if($com->rating):?>
                            <p><strong>Оценка:</strong> 
                                <span itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">
                                                                <span itemprop="ratingValue"><?php echo (int)$com->rating;?></span>
                                                                /
                                                                <span itemprop="bestRating">5</span>
                                                        </span></p>
                            <?php endif;?>

                            <div class="right-align">
                            <a class="btn btn-xs btn-default" role="button" data-toggle="collapse" href="#collapse-comment-<?php echo $com->id;?>" aria-expanded="false">
                                Комментировать
                              </a>
                            </div>    
                            <div class="collapse child-comment-container" id="collapse-comment-<?php echo $com->id;?>">
                                <strong>Ваш ответ:</strong>
                                <?php 
                                    $this->renderPartial('application.views.comment._form', array(
                                        'type'      => Comment::TYPE_COMPANY,
                                        'objectId'  => $company->id,
                                        'model'     => $comment,
                                        'hideRating'=>  true,
                                        'parentId'  =>  $com->id,
                                    ));
                                ?>
                            </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach;?>
		


<?php endif;?>

</div>  <!-- Product --> 

<?php if(sizeof($company->town->companies)>1):?>

        <h3 class="header-block header-block-green">Другие юридические компании города <?php echo CHtml::encode($company->town->name);?></h3>
        <div class="header-block-green-arrow"></div>
            <div class="container-fluid">
                <div class="row">
                <?php 
                    $companyCounter = 0;
                    $companyLimit = 6;
                ?>
                <?php foreach($company->town->companies as $com):?>
                    <?php 
                        // В блоке других юр компаний не должно быть ссылки на текущую компанию
                        if($com->id == $company->id) {
                            continue;
                        }
                        $companyCounter++;
                        if($companyCounter>$companyLimit) {
                            break;
                        }
                    ?>
                    <?php if($companyCounter%2 == 1) echo "<div class='row'>";?>

                    <div class="col-md-2">
                        <img src="<?php echo $com->getPhotoUrl('thumb');?>" alt="<?php echo CHtml::encode($com->name);?>" class="img-responsive" />
                    </div>
                    <div class="col-md-4">
                        <?php echo CHtml::link(CHtml::encode($com->name), Yii::app()->createUrl('yurCompany/view',array('id'=>$com->id)));?>
                    </div>
                    <?php if($companyCounter%2 == 0) echo "</div>";?>
                <?php endforeach;?>
                    <?php if($companyCounter%2 == 1 && $companyCounter != $companyLimit+1) echo "</div>";?>
                </div>
            </div>

<?php endif;?>

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