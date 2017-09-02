<?php
$this->setPageTitle("API для работы с лидами. ". Yii::app()->name);

?>

<h1 class="vert-margin20">API для работы с лидами</h1>

<h2>Изменение статуса лида через API 100 Юристов</h2>

<p>
Запрос отправляется методом POST на URL https://100yuristov.com/api/statusLead/
</p>
<p>
Кодировка: UTF-8
</p>
<p>
    <strong>Обязательные поля запроса:</strong><br />
code - уникальный код заявки (отправляется в письме с заявкой)<br />
status - код статуса (см. ниже)<br />
brakReason - код причины отбраковки (см. ниже), если новый статус - Отбраковка<br />
brakComment - комментарий отбраковки, если новый статус - Отбраковка<br />
</p>
<p>
<strong>Ответ вы получите в формате JSON</strong><br />
Поля ответа:<br />
Code - код ответа. 200 - ОК, другие коды - ошибка<br />
Message - текстовое сообщение ответа<br />
</p>
<p>
<strong>Доступные коды статусов:</strong><br />
3 - Отбраковка<br />
</p>
<p>
<strong>Коды причин отбраковки:</strong><br />
1 - не юридический вопрос<br />
2 - неверный номер<br />
3 - не тот регион<br />
4 - спам<br />
</p>