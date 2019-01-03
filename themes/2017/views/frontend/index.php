<?php
CController::renderPartial('webroot.themes.2017.views.frontend.include._header');
?>

<?php if((Yii::app()->user->isGuest && !(Yii::app()->controller->id=='question' && Yii::app()->controller->action->id=='create'))):?>
        <?php
        // выводим виджет с формой
            $this->widget('application.widgets.SimpleForm.SimpleForm', array());
        ?> 
    
<?php else:?>
<div class="container">
    
    <div class="top-form-replace">
        <hr/>
    </div>
    
</div>
<?php endif;?>

<div class="container">   
    <div class="row">

        <div class="col-sm-9 col-sm-push-3 col-md-9 col-md-push-3" id="center-panel">
            <?php echo $content;?>
        </div>    

        <div class="col-sm-3 col-sm-pull-9 col-md-3 col-md-pull-9" id="left-panel">

            <?php if(Yii::app()->user->isGuest):?>
                <?php
                    // выводим виджет Назойливый
                /*
                    $this->widget('application.widgets.Annoying.AnnoyingWidget', array(
                        'showAlways' => true,
                    ));
                 */
                ?>
            <?php endif;?>




            <div id="left-bar" class="vert-margin20">
                <h3 id="left-menu-switch" class="">Выберите тему</h3>
                <?php
                // выводим виджет с деревом категорий
                        $this->widget('application.widgets.CategoriesTree.CategoriesTree', array());
                ?>

            </div>
            
            <div class="vert-margin20">
            <?php
            // выводим виджет с формой
                $this->widget('application.widgets.SimpleForm.SimpleForm', array(
                    'template' => 'sidebar',
                    ));
            ?> 
            </div>

            <div>
                <?php
                $this->widget('application.widgets.RecentCategories.RecentCategories', [
                    'number' => 3,
                ]);
                ?>
            </div>
            
        </div>
    </div>
</div>
    
<?php
CController::renderPartial('webroot.themes.2017.views.frontend.include._footer');
?>