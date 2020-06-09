<?php
/* @var $this ChatController */
/* @var $model Chat */

$this->breadcrumbs = [
    'Chats' => ['index'],
    'Create',
];

$this->menu = [
    ['label' => 'List Chat', 'url' => ['index']],
    ['label' => 'Manage Chat', 'url' => ['admin']],
];
?>

<h1>Create Chat</h1>

<?php $this->renderPartial('_form', ['model' => $model]); ?>