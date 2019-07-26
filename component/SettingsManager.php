<?php
namespace bidfox\settingsManager\component;


use yii\base\Component;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;


class SettingsManager extends Component
{
    /**
     * @var string
     */
    public $modelClass;

    private $_params = false;

    /**
     * @return ISettingsQuery
     */
    protected function generateQuery(): ISettingsQuery
    {
        /** @var ISettingsModel $class */
        $class = $this->modelClass;
        return $class::find();
    }

    /**
     * @return ISettingsModel[]
     */
    protected function getSettings(): array
    {
        return $this->generateQuery()->all();
    }

    /**
     * @param bool $refresh
     *
     * @return array
     */
    protected function getParams($refresh = false): array
    {
        if($this->_params === false || $refresh){
            $this->_params = ArrayHelper::map(
                $this->getSettings(),
                function(ISettingsModel $param){
                    return $param->getParameterName();
                },
                function(ISettingsModel $param){
                    return $param->getParameterValue();
                }
            );
        }
        return $this->_params;
    }

    public function refresh(): void
    {
        if($this->_params === false) {
            return;
        }
        $this->getParams(true);

    }

    /**
     * @param $name
     * @return mixed
     * @throws \bidfox\settingsManager\component\SettingsNotDefinedException
     */
    public function getParamStrict($name)
    {
        $params = $this->getParams();
        if(!isset($params[$name])){
            throw new SettingsNotDefinedException();
        }
        $value = $params[$name];
        $json = json_decode($value, true);
        if(json_last_error() === JSON_ERROR_NONE) {
            return $json;
        }
        return $value;
    }

    /**
     * @param      $name
     * @param null $defaultValue
     * @return mixed|null
     */
    public function getParam($name, $defaultValue = null)
    {
        try{
            return $this->getParamStrict($name);
        } catch (SettingsNotDefinedException $e){
            return $defaultValue;
        }
    }

    /**
     * @param      $name
     * @param      $value
     * @param bool $json
     * @throws \bidfox\settingsManager\component\CanNotSaveSettingException
     */
    public function setParam($name, $value, $json = false): void
    {
        if ($json) {
            $value = json_encode($value);
        }

        if (null === $param = $this->generateQuery()->byName($name)->one()){
            $class = $this->modelClass;

            $param = new $class();

            /** @var ISettingsModel $param */
            $param->setParameterName($name);
        }

        $param->setParameterValue($value);

        if (!$param->saveParameter()){
            throw new CanNotSaveSettingException('Model:'.VarDumper::dumpAsString($param));
        }

        $this->refresh();
    }
}