<?php
namespace bidfox\settingsManager\component;


interface ISettingsModel
{
    /**
     * @return string
     */
    public function getParameterName();

    /**
     * @return mixed
     */
    public function getParameterValue();

    /**
     * @param $name
     */
    public function setParameterName($name);

    /**
     * @param $value
     */
    public function setParameterValue($value);

    /**
     * @return bool
     */
    public function saveParameter();

    /**
     * @return ISettingsQuery
     */
    public static function find();
}