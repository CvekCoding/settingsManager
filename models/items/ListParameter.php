<?php
namespace bidfox\settingsManager\models\items;


class ListParameter extends BaseParameter
{
    public function useParameterValue($value)
    {
        $this->value = $this->processValue(json_decode($value, true));
    }

    public function getDbParameterValue()
    {
        return json_encode($this->processValue($this->value), true);
    }

    private function processValue($value): array
    {
        if(!$value) {
            return [];
        }
        if(is_array($value)) {
            return $value;
        }
        return (array)$value;
    }
}