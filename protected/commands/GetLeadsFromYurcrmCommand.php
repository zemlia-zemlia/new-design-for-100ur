<?php
/*
 * 
 * собирает лиды из писем, расположенных в почтовом ящике admin@100yuristov.com
 */
class GetLeadsFromYurcrmCommand extends CConsoleCommand
{
    protected $towns = array(
        1036  => 'LeadLawCheboksary',
        472  =>  'LeadLawKrasnoyarsk',
		822  =>  'LeadLawSPb',
        
    );
    
    // возвращает массив мейлов из заданной папки на сервере
    protected function getEmailsFromFolder($folderName)
    {
        // параметры подключения к почтовому ящику с заявками
        $host        = Yii::app()->params['mailBoxYurcrmServer'];
        $port        = Yii::app()->params['mailBoxYurcrmPort'];
        $login       = Yii::app()->params['mailBoxYurcrmLogin'];
        $pass        = Yii::app()->params['mailBoxYurcrmPassword'];
        $param       = Yii::app()->params['mailBoxYurcrmParam'];
        $folder      = 'INBOX/' . $folderName;
        
        if(!$mbox = imap_open("{"."{$host}:{$port}{$param}"."}$folder",$login,$pass)){
            die("Couldn't open the inbox");   
        };
                
        //извлекаем письма из папки в ящике
        $emails = imap_search($mbox, 'ALL SINCE '. date('d-M-Y',strtotime("-1 day")));
        if($emails == false) {
            echo "Messages search wrong criteria";
            exit;
        }
        
        if (!count($emails)){
                exit;
            } else {
 
            // If we've got some email IDs, sort them from new to old and show them
        rsort($emails);
        
        $emailBody = array();
                
        foreach($emails as $email_id) {
                // Fetch the email's overview and show subject, from and date. 
                $overview = imap_fetch_overview($mbox,$email_id,0);  
		$emailBody[] = imap_fetchbody($mbox,$email_id,"1"); // 1.1 - потому что письмо в формате Multipart
        }
        
        imap_close($mbox);
        
        return $emailBody;
    }
    }


    public function actionIndex()
    {
        // подключаем библиотеку для парсинга текста письма
        require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'simplehtmldom_1_5/simple_html_dom.php';
        
        // будем присваивать лидам источник id=24
        $leadSourceId = 24;        
        // цена покупки лида
        $buyPrice = 30;    
        
        $existingLeads = Lead100::model()->findAll(array(
            'condition' =>  'question_date>NOW()- INTERVAL 7 DAY AND sourceId=' . $leadSourceId,
        ));
        // массив, в котором будут храниться телефоны лидов, которые добавлены в базу за последний день, чтобы не добавить одного лида несколько раз
        $existingLeadsPhones = array();
        
        foreach($existingLeads as $existingLead) {
            $existingLeadsPhones[] = Question::normalizePhone($existingLead->phone);
        }
        echo "existing leads numbers: ";
        print_r($existingLeadsPhones);
        
        foreach($this->towns as $townId=>$townAlias) {
            
            echo $townAlias . "\n\r";
            
            $emails = $this->getEmailsFromFolder($townAlias);
        
            
			
            foreach($emails as $body) {
                // Fetch the email's overview and show subject, from and date. 
                
                //print_r($body);
                
                //$bodyDecoded = base64_decode($body);
                $bodyDecoded = quoted_printable_decode($body);
                //echo $bodyDecoded;
                //continue;
                
                $name = '';
                $phone = '';
                $email = '';
                $question = '';
                    
                preg_match("/Имя:(.+)</iu", $bodyDecoded, $nameMatches);
                preg_match("/(Телефон):(.+)</iu", $bodyDecoded, $phoneMatches);
                
                $messageArray = explode("Текст вопроса:", $bodyDecoded);
                $messageWithSuffix = trim($messageArray[1]);
                $messageArray2 = explode("</p>", $messageWithSuffix);
                $message = $messageArray2[0];
                                
                $name = trim($nameMatches[1]);
                $name = str_replace("&nbsp;", " ", $name);
                $name = trim($name);
                $phone = $phoneMatches[2];
                $phone = Question::normalizePhone($phone);
                $question = $message;
                
                echo $phone . "\n\r";
                
                //print_r($nameMatches[1]);
                //print_r($phoneMatches[2]);
                //print_r($message);
                //continue;
                
                if(in_array($phone, $existingLeadsPhones)) {
                    continue;
                    // если лид с таким телефоном уже есть в базе, пропускаем его
                }
                $lead = new Lead100();
                $lead->setScenario("parsing");
                $lead->name = $name;
                $lead->phone = $phone;
                $lead->email = $email;
                $lead->question = trim($question);
                $lead->sourceId = $leadSourceId; // id нужного источника лидов
                $lead->buyPrice = $buyPrice;
                $lead->townId = $townId;
                $lead->leadStatus = Lead100::LEAD_STATUS_DEFAULT;

                if(!$lead->save()) {
                    echo $lead->phone;
                    print_r($lead->errors);
                    Yii::log($lead->getError('question') . ': ' .$lead->name, 'error', 'system.web');
                }


            }    
        } 
    }
       
    
}

?>