<?php
namespace bidfox\settingsManager\models\items;


class MinuteParameter extends BaseNumericParameter
{
    protected function getCoefficient (): float
    {
        return 60;
    }
}