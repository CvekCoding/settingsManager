<?php
namespace bidfox\settingsManager\models\items;


class HourParameter extends BaseNumericParameter
{
    protected function getCoefficient (): float
    {
        return 3600;
    }
}