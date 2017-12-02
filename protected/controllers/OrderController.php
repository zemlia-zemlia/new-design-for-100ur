<?php

/**
 * Контроллер для раздела работы с заказами документов
 */
class OrderController extends Controller {

    public $layout = '//frontend/question';

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
                //'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // allow all users 
                'actions' => array('archive'),
                'users' => array('@'),
            ),
            array('allow', // allow all users 
                'actions' => array('view'),
                'users' => array('*'),
            ),
            array('allow', // allow all users 
                'actions' => array('index'),
                'users' => array('@'),
                'expression'    =>  "Yii::app()->user->checkAccess(User::ROLE_JURIST)",
            ),
            array('allow',
                'actions' => array('setJurist'),
                'users' => array('@'),
                'expression'    =>  "Yii::app()->user->checkAccess(User::ROLE_CLIENT)",
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }
    
    /**
     * Просмотр списка активных заказов юристом
     */
    public function actionIndex()
    {
        $ordersCriteria = new CDbCriteria();
        
        $ordersCriteria->order = 'id DESC';
             
        if(isset($_GET['my']) && Yii::app()->user->role == User::ROLE_JURIST) {
            $ordersCriteria->addColumnCondition(['juristId' => Yii::app()->user->id]);
            $showMyOrders = true;
        } else {
            $ordersCriteria->addColumnCondition(['status' => Order::STATUS_CONFIRMED]);
            $showMyOrders = false;
        }
        
        $ordersDataProvider = new CActiveDataProvider('Order', [
            'criteria'  =>  $ordersCriteria,
        ]);
        
        $this->render('index', [
            'ordersDataProvider'    =>  $ordersDataProvider,
            'showMyOrders'          =>  $showMyOrders,
        ]);
    }


    /**
     * Просмотр заказа документа
     * @param integer $id id заказа
     * @throws CHttpException
     */
    public function actionView($id)
    {
        // если передан GET параметр autologin, попытаемся залогинить пользователя
        User::autologin($_GET);
        
        
        $order = Order::model()->findByPk($id);
        $commentModel = new Comment;
        $orderResponse = new OrderResponse;
        
        // проверка прав доступа - заявку может видить ее автор, юристы, админы
        
        if(!(Yii::app()->user->checkAccess(User::ROLE_JURIST) || Yii::app()->user->id == $order->userId)) {
            throw new CHttpException(403, 'Вы не можете просматривать данный заказ');
        }
        
        if (isset($_POST['Comment'])) {
            // отправлен ответ, сохраним его
            $commentModel->attributes = $_POST['Comment'];
            $commentModel->authorId = Yii::app()->user->id;

            // проверим, является ли данный комментарий дочерним для другого комментария
            if (isset($commentModel->parentId) && $commentModel->parentId > 0) {
                // является, сохраним его как дочерний комментарий
                $rootComment = Comment::model()->findByPk($commentModel->parentId);
                if ($commentModel->appendTo($rootComment)) {
                    // при appendTo происходит сохранение, офигеть
                    $this->redirect(array('/order/view', 'id' => $order->id));
                }
            } 
            // сохраняем комментарий с учетом его иерархии
            if ($commentModel->saveNode()) {
                $this->redirect(array('/order/view', 'id' => $order->id));
            }
        }
        
        // Обработка отклика юриста
        if (isset($_POST['OrderResponse'])) {
            // отправлен ответ, сохраним его
            $orderResponse->attributes = $_POST['OrderResponse'];
            $orderResponse->authorId = Yii::app()->user->id;

            // сохраняем отклик с учетом его иерархии
            if ($orderResponse->saveNode()) {
                // отправляем уведомление автору заказа
                $orderResponse->sendNotification();
                $this->redirect(array('/order/view', 'id' => $order->id));
            }
        }
        
        $this->render('view', [
            'order'         =>  $order,
            'commentModel'  =>  $commentModel,
            'orderResponse' =>  $orderResponse,
        ]);
    }
    
    /**
     * Назначает юриста исполнителем по заказу документов
     */
    public function actionSetJurist($id)
    {
        $order = Order::model()->findByPk($id);
        
        if(!$order) {
            throw new CHttpException(404, 'Заказ не найден');
        }
        
        if($order->userId != Yii::app()->user->id) {
            throw new CHttpException(403, 'Вы не можете управлять чужими заказами');
        }
        
        if($order->juristId) {
            throw new CHttpException(400, 'У этого заказа уже выбран юрист');
        }
        $juristId = (int)$_GET['juristId'];
        $jurist = User::model()->findByAttributes(['role' => User::ROLE_JURIST, 'active100' => 1, 'id' => $juristId]);
        if(!$jurist) {
            throw new CHttpException(404, 'Юрист не найден');
        }
        
        $order->juristId = $juristId;
        $order->status = Order::STATUS_JURIST_SELECTED;
        
        if($order->save()) {
            $order->sendJuristNotification();
            $this->redirect(['order/view', 'id'=>$order->id]);
        } else {
            throw new CHttpException(500, 'Не удалось назначить заказу юриста. Внутренняя ошибка.');
        }
        
    }

}