<?php
namespace bidfox\settingsManager\models\items;


class PercentParameter extends BaseNumericParameter
{
    protected function getCoefficient (): float
    {
        return 1 / 100;
    }

}