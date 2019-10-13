<?php
/*
 * собирает лиды из писем, расположенных в почтовом ящике admin@100yuristov.com 
 */
class GetLeadsFrom140Command extends CConsoleCommand
{
    // Настройки парсинга лидов из папок
    protected $folders = array(
        'SovMsk' => array(
            'sourceId'  => 29,
            'buyPrice'  => 310,
        ),
        
        
    );
    
    protected $defaultTownId = 598; // по умолчанию все лиды в этой папке из Москвы

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
        //var_dump($emails);
        if($emails == false && imap_errors()) {
            echo "Messages search wrong criteria";
            Yii::app()->end();
        }
        
        if (!count($emails) || $emails == false){
                return array();
            } else {
                print_r($emails);
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
        //$leadSourceId = 24;        
        // цена покупки лида
        //$buyPrice = 30;    
        
        foreach ($this->folders as $folder) {
            $leadSourceIds[] = $folder['sourceId'];
        }
        $existingLeads = Yii::app()->db->createCommand()
                        ->select('phone')
                        ->from('{{lead}}')
                        ->where('question_date>NOW()- INTERVAL 7 DAY')
                        ->queryAll();
        // массив, в котором будут храниться телефоны лидов, которые добавлены в базу за последний день, чтобы не добавить одного лида несколько раз
        $existingLeadsPhones = array();
        
        foreach($existingLeads as $existingLead) {
            $existingLeadsPhones[] = PhoneHelper::normalizePhone($existingLead['phone']);
        }
        //echo "existing leads numbers: ";
        //print_r($existingLeadsPhones);
        
        foreach($this->folders as $folderAlias=>$folderSettings) {
            
            //echo $folderAlias . "\n\r";
            
            $emails = $this->getEmailsFromFolder($folderAlias);
        
            //print_r($emails);
            //continue;
			
            foreach($emails as $email) {
             
                $bodyDecoded = quoted_printable_decode($email);
                $bodyDecoded = str_replace("\n", "", $bodyDecoded);
                //echo $bodyDecoded;
                //continue;
                
                $name = '';
                $phone = '';
                $email = '';
                $question = '';
                $townId = 0;
                
                preg_match("/Имя:([^<]+)</iu", $bodyDecoded, $nameMatches);
                preg_match("/Телефон:([^<]+)</iu", $bodyDecoded, $phoneMatches);
                $messageArray = explode("Сообщение:", $bodyDecoded);
                $message = trim($messageArray[1]);
                
                $phone = $phoneMatches[1];
                $phone = PhoneHelper::normalizePhone($phone);
                $name = $nameMatches[1];
                
                //echo "phone: " . $phone . PHP_EOL;
                //echo "name: " . $name . PHP_EOL;
                //echo "message: " . $message . PHP_EOL;
                
                if(in_array($phone, $existingLeadsPhones)) {
                    continue;
                    // если лид с таким телефоном уже есть в базе, пропускаем его
                }
                $lead = new Lead();
                $lead->setScenario("parsing");
                
                $lead->name = $name;
                $lead->phone = $phone;
                $lead->question = trim(str_replace('<br />', '', $message));
                //$lead->question = $message;
                $lead->sourceId = $this->folders[$folderAlias]['sourceId']; // id нужного источника лидов
                $lead->buyPrice = $this->folders[$folderAlias]['buyPrice']; // цена покупки
                $lead->townId = $this->defaultTownId;
                $lead->leadStatus = Lead::LEAD_STATUS_DEFAULT;

                if(!$lead->save()) {
                    //echo $lead->name;
                    //print_r($lead->errors);
                }

            }    
        } 
 
    }
    
}

?>