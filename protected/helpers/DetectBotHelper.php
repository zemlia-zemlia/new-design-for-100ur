<?php

namespace App\helpers;

/**
 * Класс для определения бота по Useragent
 * Class DetectBotHelper
 * @package App\helpers
 */
class DetectBotHelper
{
    private $botsNames = [];

    public function __construct(array $botsNames)
    {
        $this->botsNames= $botsNames;
    }

    /**
     * @param string $userAgent
     * @return bool
     */
    public function detectBot(string $userAgent):bool
    {
        foreach ($this->botsNames as $botName) {
            if (stristr($userAgent, $botName)) {
                return true;
            }
        }

        return false;
    }
}
