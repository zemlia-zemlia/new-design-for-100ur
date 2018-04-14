<?php
/**
 * Реализация парсера заявок из писем от сервиса 9111
 */
class EmailParser9111 extends EmailParser {

    public function parseMessage($message, Lead100 $lead, $folderSettings) {
        
        $bodyDecoded = $message; // 9111 шлют письма незашифрованными

        $this->echoDebug($bodyDecoded);

        $name = '';
        $phone = '';
        $email = '';
        $question = '';

        preg_match("/Имя:<\/b>(.+)<br>/iu", $bodyDecoded, $nameMatches);
        preg_match("/(Телефон):(.+)<br>/iu", $bodyDecoded, $phoneMatches);
        preg_match("/(Текст заявки<\/b>:)(.+)<br>/iu", $bodyDecoded, $messageMatches);

        if ($nameMatches) {
            $name = trim($nameMatches[1]);
            $name = str_replace("&nbsp;", " ", $name);
            $name = trim($name);
        }
        if ($phoneMatches) {
            $phone = $phoneMatches[2];
            $phone = Question::normalizePhone($phone);
        }
        if ($messageMatches) {
            $question = trim($messageMatches[2]);
        }
        $this->echoDebug($name . ': ' . $phone . ': '. $question);

        // если лид с таким телефоном уже есть в базе, пропускаем его
        if (in_array($phone, $this->_existingPhones)) {
            return false; 
        }

        if (!$name || !$phone) {
            return false;
        }

        $lead->setScenario("parsing");
        $lead->name = $name;
        $lead->phone = $phone;
        $lead->question = trim($question);
        if ($lead->question == '') {
            $lead->question = 'Текст вопроса потерян. Необходимо уточнить вопрос по телефону и проконсультировать';
        }
        $lead->sourceId = $folderSettings['sourceId']; // id нужного источника лидов
        $lead->buyPrice = $folderSettings['buyPrice']; // цена покупки
        $lead->townId = $folderSettings['townId']; // id города
        
        

        // в зависимости от настроек источника лидов отправляем лид на модерацию или в неразобранные
        $lead->leadStatus = $lead->leadRequiresModerationStatus();

        if (!$lead->save()) {
            $this->echoDebug($lead->phone);
            $this->echoDebug($lead->errors);
            Yii::log("Ошибка парсинга лида из почты 9111: " . $lead->name . ': ' . $lead->phone, 'error', 'system.web');
        }
        
        return true;
    }

}
