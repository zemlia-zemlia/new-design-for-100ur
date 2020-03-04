<?php

/*
 *
 * собирает лиды из писем, расположенных в почтовом ящике admin@100yuristov.com
 * в папках, начинающихся на 9111
 */

class GetLeadsFrom9111Command extends CConsoleCommand
{
    // Настройки парсинга лидов из папок
    protected $folders = [
        '9111Cheboksary' => [
            'townId' => 1036,
            'sourceId' => 33,
            'buyPrice' => 45,
        ],
        '9111Ekaterinburg' => [
            'townId' => 269,
            'sourceId' => 33,
            'buyPrice' => 50,
        ],
        '9111NNovgood' => [
            'townId' => 641,
            'sourceId' => 33,
            'buyPrice' => 50,
        ],
        '9111Rostov' => [
            'townId' => 805,
            'sourceId' => 33,
            'buyPrice' => 75,
        ],
        '9111Volgograd' => [
            'townId' => 165,
            'sourceId' => 33,
            'buyPrice' => 50,
        ],
        '9111Spb' => [
            'townId' => 822,
            'sourceId' => 33,
            'buyPrice' => 80,
        ],
        '9111Msk' => [
            'townId' => 598,
            'sourceId' => 33,
            'buyPrice' => 160,
        ],
        '9111Novosibirsk' => [
            'townId' => 666,
            'sourceId' => 33,
            'buyPrice' => 50,
        ],
        '9111Krasnoyarsk' => [
            'townId' => 472,
            'sourceId' => 33,
            'buyPrice' => 50,
        ],
        '9111Chelyabinsk' => [
            'townId' => 1039,
            'sourceId' => 33,
            'buyPrice' => 50,
        ],
        '9111Perm' => [
            'townId' => 737,
            'sourceId' => 33,
            'buyPrice' => 45,
        ],
        '9111Yaroslavl' => [
            'townId' => 1106,
            'sourceId' => 33,
            'buyPrice' => 30,
        ],
        '9111Kaluga' => [
            'townId' => 354,
            'sourceId' => 33,
            'buyPrice' => 25,
        ],
        '9111Irkutsk' => [
            'townId' => 339,
            'sourceId' => 33,
            'buyPrice' => 25,
        ],
        '9111Orenburg' => [
            'townId' => 709,
            'sourceId' => 33,
            'buyPrice' => 40,
        ],
        '9111Astrahan' => [
            'townId' => 50,
            'sourceId' => 33,
            'buyPrice' => 25,
        ],
    ];

    // возвращает массив мейлов из заданной папки на сервере
    protected function getEmailsFromFolder($folderName)
    {
        // параметры подключения к почтовому ящику с заявками
        $host = Yii::app()->params['mailBoxYurcrmServer'];
        $port = Yii::app()->params['mailBoxYurcrmPort'];
        $login = Yii::app()->params['mailBoxYurcrmLogin'];
        $pass = Yii::app()->params['mailBoxYurcrmPassword'];
        $param = Yii::app()->params['mailBoxYurcrmParam'];
        $folder = 'INBOX/' . $folderName;

        if (!$mbox = imap_open('{' . "{$host}:{$port}{$param}" . "}$folder", $login, $pass)) {
            die("Couldn't open the inbox");
        }

        //извлекаем письма из папки в ящике
        $emails = imap_search($mbox, 'UNSEEN SINCE ' . date('d-M-Y', strtotime('-1 day')));
        if (false == $emails && imap_errors()) {
            echo 'Messages search wrong criteria';
            Yii::app()->end();
        }

        if (!count($emails) || false == $emails) {
            return [];
        } else {
            //print_r($emails);
            // If we've got some email IDs, sort them from new to old and show them
            rsort($emails);

            $emailBody = [];

            foreach ($emails as $email_id) {
                // Fetch the email's overview and show subject, from and date.
                $overview = imap_fetch_overview($mbox, $email_id, 0);
                if ($overview && $overview[0] && isset($overview[0]->subject)) {
                    $emailSubject[$email_id] = imap_utf8($overview[0]->subject);
                }

                $emailBody[$email_id] = imap_fetchbody($mbox, $email_id, 2);
            }

            imap_close($mbox);

            return $emailBody;
        }
    }

    public function actionIndex()
    {
        // подключаем библиотеку для парсинга текста письма
        require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'simplehtmldom_1_5/simple_html_dom.php';

        foreach ($this->folders as $folder) {
            $leadSourceIds[] = $folder['sourceId'];
        }

        $existingLeads = Lead::model()->findAll([
            'condition' => 'question_date>NOW()- INTERVAL 2 DAY AND sourceId IN(' . implode(', ', $leadSourceIds) . ')',
        ]);

        // массив, в котором будут храниться телефоны лидов, которые добавлены в базу за последний день, чтобы не добавить одного лида несколько раз
        $existingLeadsPhones = [];

        foreach ($existingLeads as $existingLead) {
            $existingLeadsPhones[] = PhoneHelper::normalizePhone($existingLead->phone);
        }
        //echo "existing leads numbers: ";
        //print_r($existingLeadsPhones);

        foreach ($this->folders as $folderAlias => $folderSettings) {
            //echo $folderAlias . "\n\r";

            $emails = $this->getEmailsFromFolder($folderAlias);
            //echo 'messages in the folder:' . sizeof($emails). PHP_EOL;

            foreach ($emails as $emailId => $email) {
                //$bodyDecoded = imap_base64($email);
                $bodyDecoded = $email;

                //echo $bodyDecoded;

                $name = '';
                $phone = '';
                $email = '';
                $question = '';

                preg_match("/Имя:<\/b>(.+)<br>/iu", $bodyDecoded, $nameMatches);
                preg_match('/(Телефон):(.+)<br>/iu', $bodyDecoded, $phoneMatches);
                preg_match("/(Текст заявки<\/b>:)(.+)<br>/iu", $bodyDecoded, $messageMatches);

                if ($nameMatches) {
                    $name = trim($nameMatches[1]);
                    $name = str_replace('&nbsp;', ' ', $name);
                    $name = trim($name);
                }
                if ($phoneMatches) {
                    $phone = $phoneMatches[2];
                    $phone = PhoneHelper::normalizePhone($phone);
                }
                if ($messageMatches) {
                    $question = trim($messageMatches[2]);
                }

                if (in_array($phone, $existingLeadsPhones)) {
                    //echo 'duplicate! skipping' . PHP_EOL;
                    continue;
                    // если лид с таким телефоном уже есть в базе, пропускаем его
                }

                if (!$name || !$phone) {
                    continue;
                }

                $lead = new Lead();
                $lead->setScenario('parsing');
                $lead->name = $name;
                $lead->phone = $phone;
                //$lead->email = $email;
                $lead->question = trim($question);
                if ('' == $lead->question) {
                    $lead->question = 'Текст вопроса потерян. Необходимо уточнить вопрос по телефону и проконсультировать';
                }
                $lead->sourceId = $this->folders[$folderAlias]['sourceId']; // id нужного источника лидов
                $lead->buyPrice = $this->folders[$folderAlias]['buyPrice']; // цена покупки
                $lead->townId = $this->folders[$folderAlias]['townId']; // id города
                // найдем объект источника лидов для данной папки
                $source = Leadsource::model()->findByPk($lead->sourceId);

                // в зависимости от настроек источника лидов отправляем лид на модерацию или в неразобранные
                $lead->leadStatus = (0 == $source->moderation) ? Lead::LEAD_STATUS_DEFAULT : Lead::LEAD_STATUS_PREMODERATION;

                if (!$lead->save()) {
                    echo $lead->phone;
                    //print_r($lead->errors);
                    Yii::log('Ошибка парсинга лида из почты 9111: ' . $lead->name . ': ' . $lead->phone, 'error', 'system.web');
                }
            }
        }
    }
}
