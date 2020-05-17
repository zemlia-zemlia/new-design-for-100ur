<?php

namespace App\notifiers;

use App\models\Campaign;
use App\models\Lead;
use App\models\User;
use CHtml;
use GTMail;
use Yii;

/**
 * Класс, отвечающий за различные уведомления по лидам
 * Class App\notifiers\UserNotifier.
 */
class LeadNotifier
{
    /** @var GTMail */
    private $mailer;

    /** @var Lead */
    private $lead;

    public function __construct(GTMail $mailer, Lead $lead)
    {
        $this->mailer = $mailer;
        $this->lead = $lead;
    }

    /**
     * Отправка лида по почте.
     *
     * @param Campaign $campaign кампания
     *
     * @return bool
     */
    public function send(Campaign $campaign)
    {
        $this->mailer->subject = 'Заявка город ' . $this->lead->town->name . ' (' . $this->lead->town->region->name . ')';
        $this->mailer->message = '<h2>Заявка на консультацию</h2>';
        $this->mailer->message .= '<p>Имя: ' . CHtml::encode($this->lead->name) . ',</p>';
        $this->mailer->message .= '<p>Город: ' . CHtml::encode($this->lead->town->name) . ' (' . $this->lead->town->region->name . ')' . '</p>';
        $this->mailer->message .= '<p>Телефон: ' . $this->lead->phone . '</p>';
        $this->mailer->message .= '<p>Сообщение:<br />' . CHtml::encode($this->lead->question) . '</p><br /><br />';
        $this->mailer->message .= '<p>Уникальный код заявки: ' . $this->lead->secretCode . '</p>';
        $this->mailer->message .= "<p>CRM Для юристов: <a href='http://www.yurcrm.ru'>YurCRM</a></p>";

        // Вставляем ссылку на отбраковку только если у кампании процент брака больше нуля
        if ($campaign->brakPercent > 0) {
            $this->mailer->message .= '<hr /><p>'
                . "<a style='display:inline-block; padding:5px 10px; border:#999 1px solid; color:#666; background-color:#fff; text-decoration:none;' href='https://100yuristov.com/site/brakLead/?code=" . $this->lead->secretCode . "'>Отбраковка</a>"
                . '</p>';
        }

        $this->mailer->email = $campaign->buyer->email;

        $additionalHeaders = [
            'X-Postmaster-Msgtype' => 'Отправка лида',
            'List-id' => 'Отправка лида',
            'X-Mailru-Msgtype' => 'Отправка лида',
        ];

        return $this->mailer->sendMail(true, $additionalHeaders);
    }

    /**
     * Отправка покупателю уведомления о том, что его лид отправлен в Yurcrm.
     *
     * @param int $crmLeadId
     *
     * @return bool
     */
    public function sendYurcrmNotification(User $buyer, $crmLeadId)
    {
        $this->mailer->subject = 'Заявка на консультацию из города ' . $this->lead->town->name . ' (' . $this->lead->town->region->name . ')';
        $this->mailer->message = '<h2>Заявка на консультацию</h2>';
        $this->mailer->message .= '<p>Имя: ' . CHtml::encode($this->lead->name) . ',</p>';
        $this->mailer->message .= '<p>Город: ' . CHtml::encode($this->lead->town->name) . ' (' . $this->lead->town->region->name . ')' . '</p>';

        $this->mailer->message .= "<p>Просмотреть заявку в <a href='" . Yii::app()->params['yurcrmDomain'] . '/contact/view?id=' . $crmLeadId . "'>YurCRM</a></p>";
        $this->mailer->message .= '<p>При первом входе в CRM вам нужно будет воспользоваться функцией восстановления пароля.  В качестве Email используйте адрес, под которым вы зарегистрированы в 100 Юристах</p>';
        $this->mailer->email = $buyer->email;

        $additionalHeaders = [
            'X-Postmaster-Msgtype' => 'Отправка лида',
            'List-id' => 'Отправка лида',
            'X-Mailru-Msgtype' => 'Отправка лида',
        ];

        return $this->mailer->sendMail(true, $additionalHeaders);
    }
}
