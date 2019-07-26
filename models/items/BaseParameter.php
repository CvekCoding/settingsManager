<?php
namespace bidfox\settingsManager\models\items;


use bidfox\settingsManager\component\CanNotSaveSettingException;
use bidfox\settingsManager\models\ParameterEvent;
use bidfox\settingsManager\component\ISettingsModel;
use yii\base\Component;


abstract class BaseParameter extends Component implements IParameter
{
    private $_old_value;

    /** @var mixed */
    public $value;

    /** @var string */
    private $_db_name;

    /** @var string */
    private $_form_name;

    /** @var ISettingsModel */
    private $_model;

    private $_description;

    /**
     * BaseParameter constructor.
     *
     * @param string $db_name
     * @param string $form_name
     * @param mixed $value
     * @param string $description
     */
    public function __construct($db_name, $form_name, $value = null, $description = null)
    {
        parent::__construct([]);
        $this->_db_name     = $db_name;
        $this->_form_name   = $form_name;
        $this->value        = $value;
        $this->_old_value   = $value;
        $this->_description = $description;
    }

    public function getDbParameterName(): string
    {
        return $this->_db_name;
    }

    public function getFormParameterName(): string
    {
        return $this->_form_name;
    }

    public function getParameterDescription()
    {
        return $this->_description;
    }

    public function load(ISettingsModel $parameter): void
    {
        $this->_model = $parameter;

        $this->useParameterValue($parameter->getParameterValue());

        $this->_old_value = $this->value;
    }

    public function getValue()
    {
        return $this->value;
    }

    abstract public function useParameterValue($value);

    abstract public function getDbParameterValue();

    /**
     * @return bool
     * @throws \Exception
     */
    public function save(): bool
    {
        $this->_model->setParameterValue($this->getDbParameterValue());

        if(!$this->_model->saveParameter()){
            throw new CanNotSaveSettingException('Не удалось сохранить настройку '.$this->getDbParameterName().' ('.$this->value.')');
        }

        if($this->value != $this->_old_value) {
            $this->trigger(self::EVENT_ON_CHANGE, new ParameterEvent($this->_old_value, $this->value));
        }

        $this->_old_value = $this->value;

        return true;
    }

}