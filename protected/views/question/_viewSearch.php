<div class="question-search-item"> 
    <div class="vert-margin10">
        
        <div style="background-color: #f0f8ff;">
            <div class="row inside">
                <div class="col-md-10">
                    <p>
                    <?php if($data['title']):?>
                        <strong><?php echo CHtml::link(CHtml::encode($data['title']), Yii::app()->createUrl('question/view', array('id'=>$data['id']))); ?></strong>
                    <?php endif;?>
					</p>

                    
                    <small>
                    <?php if($data['townName']):?>
                        <span class="glyphicon glyphicon-map-marker text-muted"></span>&nbsp;
                        <?php echo CHtml::encode($data['townName']);?>
                        &nbsp;&nbsp;
                    <?php endif;?>
                        
                    <?php if(isset($data['answersCount'])):?>
                        <?php echo $data['answersCount'] . " " . CustomFuncs::numForms($data['answersCount'], "ответ", "ответа", "ответов");?>
                    <?php endif;?>   
                    </small>
                    
                </div>
                <div class="col-md-2">
                    <?php echo CHtml::link('Открыть', Yii::app()->createUrl('question/view', array('id'=>$data['id'])), array('class'=>'btn btn-xs btn-default btn-block')); ?>
                </div>
            </div>
        </div>
        
    </div> 

</div>