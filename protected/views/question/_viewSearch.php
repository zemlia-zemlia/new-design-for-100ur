<div class="question-search-item"> 
    <div class="panel-body">
        
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-10" style="border: #dad8d8 1px solid;">
                    <p>
                    <?php if($data['title']):?>
                        <strong><?php echo CHtml::link(CHtml::encode($data['title']), Yii::app()->createUrl('question/view', array('id'=>$data['id']))); ?></strong>
                    <?php endif;?>
					</p>

                    <p >
                    <small>
                    <?php if($data['publishDate']):?>
                        <!-- 
                        <?php
                            $pubTime = strtotime($data['publishDate']);
                            $labelClass = (time()-$pubTime<86400)?'label-danger':'label-default';
                        ?> 
                        
                        <span class="label <?php echo $labelClass;?>"><?php echo CustomFuncs::niceDate($data['publishDate'], false, false);?></span>
                        <?php echo CustomFuncs::showTime($data['publishDate']);?>
                    <?php endif;?>&nbsp; -->
						
                    <?php if($data['townName']):?>
                        <span class="glyphicon glyphicon-map-marker"></span>&nbsp;
                        <?php echo CHtml::encode($data['townName']);?>
                        &nbsp;&nbsp;
                    <?php endif;?>
                        
                    <?php if(isset($data['answersCount'])):?>
                        <?php echo $data['answersCount'] . " " . CustomFuncs::numForms($data['answersCount'], "ответ", "ответа", "ответов");?>
                    <?php endif;?>   
                    </small>
                    
                    </p>
                </div>
                <div class="col-md-2">
                    <?php echo CHtml::link('Ответить', Yii::app()->createUrl('question/view', array('id'=>$data['id'])), array('class'=>'btn btn-xs btn-default btn-block')); ?>
                </div>
            </div>
        </div>
        
    </div>    
</div>