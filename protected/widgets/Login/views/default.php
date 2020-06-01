<?php use App\models\LoginForm;
use App\models\User;

if (Yii::app()->user->isGuest):?>


        <?php $form = $this->beginWidget('CActiveForm', [
                'id' => 'login-form-widget',
                'action' => Yii::app()->createUrl('site/login'),
                'enableAjaxValidation' => false,
                'htmlOptions' => [
                    'class' => '',
                ],
        ]); ?>

        <?php
            if (!isset($model)) {
                $model = new LoginForm();
            }
        ?>

        <h3 class="header-block header-block-light-grey header-icon-user">Вход на сайт</h3>
        <div class="header-block-light-blue-arrow" style="width:50px;"></div>
        
        <div class="inside">
            <div class="form-group">

                <?php echo $form->textField($model, 'email', ['class' => 'form-control input-sm', 'placeholder' => $model->getAttributeLabel('email')]); ?>

                <?php echo $form->error($model, 'email'); ?>
            </div>



            <div class="form-group">

                <?php echo $form->passwordField($model, 'password', ['class' => 'form-control input-sm', 'placeholder' => $model->getAttributeLabel('password')]); ?>

                <?php echo $form->error($model, 'password'); ?>
            </div>

            <div class="form-group">
				<div class="col-md-6">
					<?php echo $form->checkBox($model, 'rememberMe'); ?>

					<?php echo $model->getAttributeLabel('rememberMe'); ?>

					<?php echo $form->error($model, 'rememberMe'); ?>
				</div>
				<div class="col-md-6 small">
					<p>
						<?php echo CHtml::link('Забыли пароль?', Yii::app()->createUrl('user/restorePassword')); ?>
					</p>
				</div>
            </div>
            
            <div class="row">
                <div class="col-md-12">
                    <p><?php echo CHtml::submitButton('Войти', ['class' => 'button button-blue-gradient btn-block text-uppercase']); ?></p>
                </div>
                
                <div class="col-md-12">
                    <p><?php echo CHtml::link('Регистрация', Yii::app()->createUrl('user/create'), ['class' => 'button button-blue-gradient btn-block text-uppercase']); ?></p>
                </div>
                
            </div>
        </div>



        <?php $this->endWidget(); ?>

<?php else:?>
    <div class="profile-block">
        <div class="profile-block-container flat-panel inside">
            <div class="row">
                <div class="col-sm-4 col-xs-6">
                    <img src="<?php echo Yii::app()->user->getAvatarUrl(); ?>" alt="Аватар" class="img-bordered" />
                </div>
                <div class="col-sm-8 col-xs-6">
                        <div>
                        <?php echo CHtml::link(('' != Yii::app()->user->lastName) ? Yii::app()->user->shortName : CHtml::encode(Yii::app()->user->name), Yii::app()->createUrl((User::ROLE_BUYER == Yii::app()->user->role) ? '/buyer' : '/user')); ?>
                        <?php echo CHtml::link('<span class="glyphicon glyphicon-log-out"></span>', Yii::app()->createUrl('site/logout'), []); ?>
                        </div>
                        

                        <?php if (User::ROLE_BUYER == Yii::app()->user->role || User::ROLE_PARTNER == Yii::app()->user->role):?>
                            <small>
                                <div>
                                    <?php
                                        // найдем баланс пользователя. если это не вебмастер:
                                        if (User::ROLE_PARTNER != Yii::app()->user->role) {
                                            $balance = Yii::app()->user->balance;
                                            $transactionPage = '/buyer/transactions';
                                        } else {
                                            // если это вебмастер, кешируем баланс, рассчитанный из транзакций вебмастера
                                            $balance = $currentUser->calculateWebmasterBalance(30);
                                            $transactionPage = '/webmaster/transaction/index';
                                        }
                                    ?>
                                    Баланс: <?php echo CHtml::link(MoneyFormat::rubles($balance), Yii::app()->createUrl($transactionPage)); ?> руб.
                                    
                                    
                                </div>
                            </small>
                        <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
<?php endif; ?>
