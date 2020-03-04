<?php

/**
 * Работа с UTM метками
 * Class UTMHelper.
 */
class UTMHelper
{
    /**
     * вставляет во все ссылки в сообщении utm метки.
     *
     * @param string $text Исходный текст
     * @param array  $tags Массив UTM-меток
     *
     * @return string
     */
    public static function insertTags($text, $tags = [])
    {
        if (empty($tags)) {
            return $text;
        }

        $tagsString = '';
        $tagsString .= (isset($tags['utm_medium'])) ? '&utm_medium=' . urlencode($tags['utm_medium']) : '';
        $tagsString .= (isset($tags['utm_source'])) ? '&utm_source=' . urlencode($tags['utm_source']) : '';
        $tagsString .= (isset($tags['utm_campaign'])) ? '&utm_campaign=' . urlencode($tags['utm_campaign']) : '';
        $tagsString .= (isset($tags['utm_term'])) ? '&utm_term=' . urlencode($tags['utm_term']) : '';
        $tagsString .= (isset($tags['utm_content'])) ? '&utm_content=' . urlencode($tags['utm_content']) : '';

        $text = preg_replace("/href=(['|\"]{1})([^?'\"]*)[?]{0,1}([^'\"]*)/", 'href=$1$2?$3' . $tagsString, $text);
        $text = preg_replace('/\?&utm/', '?utm', $text);

        return $text;
    }
}
