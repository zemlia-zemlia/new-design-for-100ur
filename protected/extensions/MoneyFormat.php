<?php
use Money\Currencies\ISOCurrencies;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Money as MoneyLib;

class MoneyFormat
{
    public static function rubles($valueMinor = 0)
    {
        $currencies = new ISOCurrencies();
        $moneyFormatter = new DecimalMoneyFormatter($currencies);

        return $moneyFormatter->format(MoneyLib::RUB($valueMinor));
    }
}