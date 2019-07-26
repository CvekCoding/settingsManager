<?php
namespace bidfox\settingsManager\models;


use bidfox\settingsManager\component\ISettingsModel;
use bidfox\settingsManager\models\items\IParameter;
use yii\helpers\ArrayHelper;
use yii\base\Model;


abstract class SettingsForm extends Model
{
    /** @var IParameter[] */
    private $_parameters;

    /**
     * @return ISettingsModel[]
     */
    abstract protected function fetchParameterModels(): array ;

    /**
     * @return IParameter[]
     */
    abstract protected function fetchParameters(): array ;

    public function getParameters()
    {
        return $this->_parameters;
    }

    /**
     * @return ISettingsModel
     */
    abstract protected function generateParameterModel(): ISettingsModel;

    public function init()
    {
        parent::init();

        $this->_parameters = ArrayHelper::index($this->fetchParameters(), function(IParameter $parameter){
            $model = $this->generateParameterModel();
            $model->setParameterName($parameter->getDbParameterName());
            $parameter->load($model);
            return $parameter->getFormParameterName();
        });

        /** @var IParameter[] $models */
        $models = ArrayHelper::index($this->_parameters, function(IParameter $parameter){
            return $parameter->getDbParameterName();
        });

        foreach($this->fetchParameterModels() as $parameter) {
            if(isset($models[$parameter->getParameterName()])) {
                $models[$parameter->getParameterName()]->load($parameter);
            }
        }
    }

    protected function saveInternal(): bool
    {
        foreach ($this->_parameters as $parameter) {
            if(!$parameter->save()){
                return false;
            }
        }

        return true;
    }

    public function save(): bool
    {
        if(!$this->validate()) {
            return false;
        }

        return $this->saveInternal();
    }

    /**
     * @param string $name
     * @return mixed
     * @throws \yii\base\UnknownPropertyException
     */
    public function __get($name)
    {
        if(isset($this->_parameters[$name])) {
            return $this->_parameters[$name]->value;
        }

        return parent::__get($name);
    }

    /**
     * @param string $name
     * @param mixed  $value
     * @return mixed|void
     * @throws \yii\base\UnknownPropertyException
     */
    public function __set($name, $value)
    {
        if(isset($this->_parameters[$name])) {
            $this->_parameters[$name]->value = $value;
            return;
        }

        parent::__set($name, $value);
    }


}