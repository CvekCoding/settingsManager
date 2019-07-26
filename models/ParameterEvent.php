<?php
namespace bidfox\settingsManager\models;


use yii\base\Event;


class ParameterEvent extends Event
{
    private $_old_value;
    private $_new_value;

    public function __construct($oldValue, $newValue, array $config = [])
    {
        parent::__construct($config);
        $this->_old_value = $oldValue;
        $this->_new_value = $newValue;
    }

    /**
     * @return mixed
     */
    public function getOldValue()
    {
        return $this->_old_value;
    }

    /**
     * @return mixed
     */
    public function getNewValue()
    {
        return $this->_new_value;
    }
}