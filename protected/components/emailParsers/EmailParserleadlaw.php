<?php

class EmailParserleadlaw extends EmailParser {
    
    protected function getFetchBodySection()
    {
        return 1;
    }

    public function parseMessage(ParsedEmail $message, Lead $lead, $folderSettings) {
        $bodyDecoded = quoted_printable_decode($message->getBody());
        
        $this->echoDebug($bodyDecoded);
                
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

        $this->echoDebug($name . ': ' . $phone . ': '. $question);
        
        // если лид с таким телефоном уже есть в базе, пропускаем его
        if (in_array($phone, $this->_existingPhones)) {
            return false;
        }
        
        $lead->setScenario("parsing");
        $lead->name = $name;
        $lead->phone = $phone;
        $lead->question = trim($question);
        $lead->sourceId = $folderSettings['sourceId']; // id нужного источника лидов
        $lead->buyPrice = $folderSettings['buyPrice']; // цена покупки
        $lead->townId = $folderSettings['townId']; // id города
        $lead->leadStatus = Lead::LEAD_STATUS_DEFAULT;

        if (!$lead->save()) {
            $this->echoDebug($lead->phone);
            $this->echoDebug($lead->errors);
            Yii::log('Ошибка парсинга лида из папки ящика Leadlaw ' . ' : ' . $lead->getError('question') . ': ' . $lead->name, 'error', 'system.web');
        }
        
        return true;
    }

}
