<?php

/*
 * 
 * собирает лиды из писем, расположенных в почтовом ящике admin@100yuristov.com
 * в папках, начинающихся на 9111
 */

class GetLeadsFrom9111Command extends CConsoleCommand {

    // Настройки парсинга лидов из папок
    protected $folders = array(
        '9111Cheboksary' => array(
            'townId' => 1036,
            'sourceId' => 33,
            'buyPrice' => 45,
        ),
        '9111Ekaterinburg' => array(
            'townId' => 269,
            'sourceId' => 33,
            'buyPrice' => 50,
        ),
        '9111NNovgood' => array(
            'townId' => 641,
            'sourceId' => 33,
            'buyPrice' => 50,
        ),
        '9111Rostov' => array(
            'townId' => 805,
            'sourceId' => 33,
            'buyPrice' => 60,
        ),
        '9111Volgograd' => array(
            'townId' => 165,
            'sourceId' => 33,
            'buyPrice' => 50,
        ),
		'9111Spb' => array(
            'townId' => 822,
            'sourceId' => 33,
            'buyPrice' => 150,
        ),
		'9111Msk' => array(
            'townId' => 598,
            'sourceId' => 33,
            'buyPrice' => 150,
        ),
		'9111Novosibirsk' => array(
            'townId' => 666,
            'sourceId' => 33,
            'buyPrice' => 50,
        ),
		'9111Krasnoyarsk' => array(
            'townId' => 472,
            'sourceId' => 33,
            'buyPrice' => 50,
        ),
		'9111Chelyabinsk' => array(
            'townId' => 1039,
            'sourceId' => 33,
            'buyPrice' => 50,
        ),
		'9111Perm' => array(
            'townId' => 737,
            'sourceId' => 33,
            'buyPrice' => 45,
        ),
		'9111Yaroslavl' => array(
            'townId' => 1106,
            'sourceId' => 33,
            'buyPrice' => 30,
        ),
		
    );

    // возвращает массив мейлов из заданной папки на сервере
    protected function getEmailsFromFolder($folderName) {
        // параметры подключения к почтовому ящику с заявками
        $host = Yii::app()->params['mailBoxYurcrmServer'];
        $port = Yii::app()->params['mailBoxYurcrmPort'];
        $login = Yii::app()->params['mailBoxYurcrmLogin'];
        $pass = Yii::app()->params['mailBoxYurcrmPassword'];
        $param = Yii::app()->params['mailBoxYurcrmParam'];
        $folder = 'INBOX/' . $folderName;

        if (!$mbox = imap_open("{" . "{$host}:{$port}{$param}" . "}$folder", $login, $pass)) {
            die("Couldn't open the inbox");
        };

        //извлекаем письма из папки в ящике
        $emails = imap_search($mbox, 'UNSEEN SINCE ' . date('d-M-Y', strtotime("-1 day")));
        if ($emails == false && imap_errors()) {
            echo "Messages search wrong criteria";
            exit;
        }

        if (!count($emails) || $emails == false) {
            return array();
        } else {
            //print_r($emails);
            // If we've got some email IDs, sort them from new to old and show them
            rsort($emails);

            $emailBody = array();

            foreach ($emails as $email_id) {
                // Fetch the email's overview and show subject, from and date. 
                $overview = imap_fetch_overview($mbox, $email_id, 0);
                $emailBody[] = imap_fetchbody($mbox, $email_id, 2);
            }

            imap_close($mbox);

            return $emailBody;
        }
    }

    public function actionIndex() {
        // подключаем библиотеку для парсинга текста письма
        require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'simplehtmldom_1_5/simple_html_dom.php';

        foreach ($this->folders as $folder) {
            $leadSourceIds[] = $folder['sourceId'];
        }
        
        $existingLeads = Lead100::model()->findAll(array(
            'condition' => 'question_date>NOW()- INTERVAL 2 DAY AND sourceId IN(' . implode(', ', $leadSourceIds) . ')',
        ));
        
        // массив, в котором будут храниться телефоны лидов, которые добавлены в базу за последний день, чтобы не добавить одного лида несколько раз
        $existingLeadsPhones = array();

        foreach ($existingLeads as $existingLead) {
            $existingLeadsPhones[] = Question::normalizePhone($existingLead->phone);
        }
        //echo "existing leads numbers: ";
        //print_r($existingLeadsPhones);

        foreach ($this->folders as $folderAlias => $folderSettings) {

            //echo $folderAlias . "\n\r";

            $emails = $this->getEmailsFromFolder($folderAlias);

            foreach ($emails as $email) {

                $bodyDecoded = imap_base64($email);
                //echo $bodyDecoded;
                
                $name = '';
                $phone = '';
                $email = '';
                $question = '';

                preg_match("/Имя:<\/b>(.+)<br>/iu", $bodyDecoded, $nameMatches);
                preg_match("/(Телефон):(.+)<br>/iu", $bodyDecoded, $phoneMatches);
                preg_match("/(Текст заявки<\/b>:)(.+)<br>/iu", $bodyDecoded, $messageMatches);
                
                if($nameMatches) {
                    $name = trim($nameMatches[1]);
                    $name = str_replace("&nbsp;", " ", $name);
                    $name = trim($name);
                }
                if($phoneMatches) {
                    $phone = $phoneMatches[2];
                    $phone = Question::normalizePhone($phone);
                }
                if($messageMatches) {
                    $question = trim($messageMatches[2]);
                }
                //echo $name . ': ' . $phone . ': '. $question . "\n\r";

                //print_r($nameMatches[1]);
                //print_r($phoneMatches[2]);
                //print_r($message);
                //continue;

                if (in_array($phone, $existingLeadsPhones)) {
                    //echo 'duplicate! skipping' . PHP_EOL;
                    continue;
                    // если лид с таким телефоном уже есть в базе, пропускаем его
                }
                
                if(!$name || !$phone) {
                    continue;
                }
                
                //exit; // testing, not save
                
                $lead = new Lead100();
                $lead->setScenario("parsing");
                $lead->name = $name;
                $lead->phone = $phone;
                //$lead->email = $email;
                $lead->question = trim($question);
                $lead->sourceId = $this->folders[$folderAlias]['sourceId']; // id нужного источника лидов
                $lead->buyPrice = $this->folders[$folderAlias]['buyPrice']; // цена покупки
                $lead->townId = $this->folders[$folderAlias]['townId']; // id города
                $lead->leadStatus = Lead100::LEAD_STATUS_DEFAULT;

                if (!$lead->save()) {
                    echo $lead->phone;
                    //print_r($lead->errors);
                    Yii::log($lead->getError('question') . ': ' . $lead->name, 'error', 'system.web');
                }
            }
        }
    }

}

?>