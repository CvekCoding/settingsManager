<?php
namespace bidfox\settingsManager\models\items;


abstract class BaseNumericParameter extends BaseParameter
{
    abstract protected function getCoefficient(): float;

    public function useParameterValue($value)
    {
        $this->value = is_numeric($value) ? $value / $this->getCoefficient() : null;
    }

    public function getDbParameterValue()
    {
        if(is_numeric($this->value)) {
            return (string)$this->value * $this->getCoefficient();
        }
        return null;
    }

}