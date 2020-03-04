<?php
/* @var $this UserController */
/* @var $model User */
$this->pageTitle = 'API для отправки лидов.' . Yii::app()->name;
?>

<h1>Отправка лидов через API</h1>
<div class="box">
    <div class="box-body">
        <p>
            Запрос отправляется методом POST на URL https://100yuristov.com/api/sendLead/
        </p>
        <p>
            Кодировка: UTF-8
        </p>
        <p>
            Обязательные поля запроса:<br/>
            phone - номер телефона (любой формат, важны только первые 10 цифр)<br/>
            name - имя клиента<br/>
            town - название города на русском<br/>
            question - текст вопроса<br/>
            appId - уникальный id кампании партнера<br/>
            signature - подпись для проверки пришедших данных
        </p>
        <p>
            Правило вычисления подписи:<br/>
            <code>
                signature = md5(name.phone.town.question.appId.secretKey)
            </code><br/>
            где secretKey - ваш секретный ключ кампании
        </p>
        <p>
            Необязательные поля:<br/>
            email - Email клиента
        </p>

        Ответ от сервера:
        <p>
            Вы получаете ответ в формате JSON со следующими полями:<br/>
            code - код ответа. 200 - лид принят, остальные коды означают ошибку<br/>
            message - сообщение от сервера с описанием ошибки либо информация об успешной операции
        </p>

        <h2>PHP класс для работы с API</h2>
        <p>
            Скачайте и распакуйте <a href="https://100yuristov.com/upload/StoYuristovClient.zip">архив</a>
        </p>
        <p>
            Подключите класс в своем приложении:
        </p>
        <code>
            require_once("путь_к_файлу/StoYuristovClient.php");
        </code><br/>
        <p>
            Инициализируйте объект:
        </p>
        <code>
            $apiClient = new StoYuristovClient(ваш_appId, "ваш_секретный_ключ", 0);
        </code><br/>
        <p>
            Третий параметр - включение тестового режима (по умолчанию 0 - выкл, 1 - вкл.). В тестовом режиме лиды
            принимаются и
            проверяются, но не сохраняются.
        </p>
        <p>
            Присвойте объекту необходимые свойства:
        </p>
        <code>
            $apiClient->name = "имя";<br/>
            $apiClient->phone = "номер телефона";<br/>
            $apiClient->town = "Название города";<br/>
            $apiClient->question = "текст вопроса";<br/>
        </code>
        <p>
            Перед отправкой автоматически рассчитается подпись, вручную указывать ее не нужно.<br/>
            Отправка лида:
        </p>
        <code>
            $apiResult = $apiClient->send();
        </code>
        <p>
            Результат метода - ассоциативный массив.<br/>
            Поля:<br/>
            code - код ответа. 200 - все ОК.<br/>
            message - описание ответа<br/>
            errors - массив с описанием ошибок, если они есть
        </p>
    </div>
</div>