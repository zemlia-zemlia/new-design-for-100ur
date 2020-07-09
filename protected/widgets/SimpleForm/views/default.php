<?php
use App\models\Question;
use App\models\User;
?>

<section class="header__main">
    <div class="header__main-bg">
        <img src="img/header-main-bg.png" alt="">
    </div>
    <div class="container">
        <div class="row justify-content-center align-items-end">
            <div class="col-sm-10 col-md-7 col-lg-6 offset-xl-1">
                <div class="header__main-content">
                    <h1 class="header__main-title"><div class="header__main-title--block">Юридическая</div>консультация <span class="header__main-title--colored">бесплатно</span></h1>
                    <div class="header__main-subtitle">Задай вопрос юристу онлайн</div>
                    <?php $form = $this->beginWidget('CActiveForm', [
                        'htmlOptions' => ['class' => 'header__main-form'],
                        'enableAjaxValidation' => false,
                        'action' => Yii::app()->createUrl('question/create') . '?utm_source=100yuristov&utm_medium=hero&utm_campaign=' . Yii::app()->controller->id,
                    ]); ?>
                    <form action="" class="header__main-form">
                        <div class="header__main-input">
                            <?= $form->textField($model, 'authorName', ['class' => '', 'placeholder' => 'Как вас зовут?']); ?>
                            <?= $form->error($model, 'authorName'); ?>

                        </div>
                        <div class="header__main-textarea">
                            <?= $form->textArea($model, 'questionText',
                                ['class' => '', 'rows' => 6, 'placeholder' =>
                                    'Опишите вашу проблему...']);
                            ?>
                            <?= $form->error($model, 'questionText'); ?>
                        </div>
                        <div class="header__main-bottom">
                            <?= CHtml::submitButton($model->isNewRecord ? 'Задать вопрос' : 'Сохранить',
                                ['class' => 'header__main-btn header__main-btn-ask', 'onclick' => 'yaCounter26550786.reachGoal("simple_form_submit"); return true;']);
                            ?>

                            <a href="/question/call/" class="header__main-btn header__main-order-btn">Заказать звонок</a>
                            <div class="header__main-order-desc">
                                <div class="header__main-order-desc-value">Не хотите писать?</div>
                                <div class="header__main-order-desc-value">Закажите обратный звонок</div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-3 col-lg-4 offset-lg-1 header__main-img-wrap">
                <div class="header__main-img">
                    <img src="img/header-main-img.png" alt="">
                </div>
            </div>
        </div>
    </div>
</section>




                
<?php $this->endWidget(); ?>
                


        <?php
            $questionsCountInt = Question::getCount() * 2;
            $questionsCount = str_pad((string) $questionsCountInt, 6, '0', STR_PAD_LEFT);
            $numbers = str_split($questionsCount);
            $answersCount = round($questionsCountInt * 1.684);
            $numbersAnswers = str_split($answersCount);

            $yuristsCountRow = Yii::app()->db->createCommand()
                    ->select('COUNT(*) counter')
                    ->from('{{user}}')
                    ->where('role=:role', [':role' => User::ROLE_JURIST])
                    ->queryRow();
            $yuristsCount = round($yuristsCountRow['counter'] * 5.314);
        ?>


<!-- Activity -->
<section class="activity">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-3">
                <div class="activity__item">
                    <div class="activity__item-value"><?= $questionsCountInt; ?></div>
                    <div class="activity__item-desc">Вопросов задано</div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="activity__item">
                    <div class="activity__item-value"><?= $answersCount; ?></div>
                    <div class="activity__item-desc">Ответов получено</div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="activity__item">
                    <div class="activity__item-value"><?= $yuristsCount; ?></div>
                    <div class="activity__item-desc">Юристов на сайте</div>
                </div>
            </div>
        </div>
    </div>
</section>

