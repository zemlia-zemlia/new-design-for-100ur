<?php
use Money\Currencies\ISOCurrencies;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Money as MoneyLib;

class MoneyFormat
{
    public static function rubles($valueMinor = 0, $withThousandsSeparator = false)
    {
        $currencies = new ISOCurrencies();
        $moneyFormatter = new DecimalMoneyFormatter($currencies);

        return ($withThousandsSeparator == true) ?
            number_format((float)$moneyFormatter->format(MoneyLib::RUB($valueMinor)), 2, '.', ' ') :
            $moneyFormatter->format(MoneyLib::RUB($valueMinor))
            ;
    }
}
