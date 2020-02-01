<?php
/**
 * Реализация парсера заявок из писем от сервиса 9111
 */
class EmailParser9111 extends EmailParser
{
    protected function getFetchBodySection()
    {
        return 2;
    }

    public function parseMessage(ParsedEmail $message, Lead $lead, $folderSettings)
    {
        $bodyDecoded = $message->getBody();
        
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
            $phone = PhoneHelper::normalizePhone($phone);
        }
        if ($messageMatches) {
            $question = trim($messageMatches[2]);
        }
        $this->echoDebug($name . ': ' . $phone . ': '. $question);
        
        /*
         * Если не удалось распарсить текст письма, возможно оно закодировано в quoted_printable
         * Пытаемся распарсить раскодированную версию письма
         */
        if (!$name && !$phone && !$question) {
            $message->setBody(quoted_printable_decode($bodyDecoded));
            return $this->parseMessage($message, $lead, $folderSettings);
        }

        // если лид с таким телефоном уже есть в базе, пропускаем его
        if (in_array($phone, $this->_existingPhones)) {
            return false;
        }

        if (!$name && !$phone) {
            return false;
        }

        $lead->setScenario("parsing");
        
        $lead->sourceId = $folderSettings['sourceId']; // id нужного источника лидов
        $lead->buyPrice = $folderSettings['buyPrice'] * 100; // цена покупки, переводим в копейки
        
        if ($message->getSubject() == 'Телефонный трафик (8-800)') {
            // это письмо с отчетом о звонке
            $lead->name = "Звонок";
            $lead->type = Lead::TYPE_INCOMING_CALL;
        } else {
            // это письмо с лидом
            $lead->name = $name;
            // в зависимости от настроек источника лидов отправляем лид на модерацию или в неразобранные
            $lead->leadStatus = $lead->leadRequiresModerationStatus();
        }
        
        $lead->townId = $folderSettings['townId']; // id города
        $lead->phone = $phone;
        $lead->question = trim($question);
        if ($lead->question == '') {
            $lead->question = 'Клиент звонил на горячую линию для получения консультации. Уточните вопрос по телефону.';
        }
        
        if (!$lead->save()) {
            $this->echoDebug($lead->phone);
            Yii::log("Ошибка парсинга лида из почты 9111: " . $lead->name . ': ' . $lead->phone, 'error', 'system.web');
        }
        
        return true;
    }
}
