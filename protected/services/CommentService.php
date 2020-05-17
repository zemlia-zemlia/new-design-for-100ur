<?php

namespace App\services;

use App\models\Comment;
use App\models\User;

/**
 * Бизнес-логика работы с комментариями
 * Class CommentService
 * @package App\services
 */
class CommentService
{
    /**
     * Создание комментария
     * @param array $attributes
     * @param \IWebUser $currentUser
     * @return Comment
     */
    public function create(array $attributes, \IWebUser $currentUser): Comment
    {
        $commentModel = new Comment;

        // отправлен ответ, сохраним его
        $commentModel->attributes = $attributes;
        $commentModel->authorId = $currentUser->getId();

        // комментарии от юристов сразу помечаем как проверенные
        if (User::ROLE_JURIST == $currentUser->getRole()) {
            $commentModel->status = Comment::STATUS_CHECKED;
        }

        // проверим, является ли данный комментарий дочерним для другого комментария
        if (isset($commentModel->parentId) && $commentModel->parentId > 0) {
            // является, сохраним его как дочерний комментарий
            $rootComment = Comment::model()->findByPk($commentModel->parentId);
            $commentModel->appendTo($rootComment);
        }

        // сохраняем комментарий с учетом его иерархии
        $commentModel->saveNode();

        return $commentModel;
    }
}
