<?php

/**
 * Интерфейс классов для работы с API партнерских программ
 */
interface ApiClassInterface
{
    public function send(Lead $lead);
}
