<?php
namespace bidfox\settingsManager\models\items;


class SimpleParameter extends BaseParameter
{
    public function useParameterValue($value)
    {
        $this->value = $value;
    }

    public function getDbParameterValue()
    {
        return $this->value;
    }

}