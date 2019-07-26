<?php
namespace bidfox\settingsManager\models\items;


use bidfox\settingsManager\component\ISettingsModel;


interface IParameter
{
    const EVENT_ON_CHANGE = 'change';
    /**
     * @return string
     */
    public function getDbParameterName(): string ;

    /**
     * @return string
     */
    public function getFormParameterName(): string ;

    public function load(ISettingsModel $parameter): void;

    public function getParameterDescription();

    public function getValue();

    public function save(): bool ;

    public function on($name, $handler, $data = null, $append = true);
}