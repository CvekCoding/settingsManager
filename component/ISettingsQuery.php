<?php
namespace bidfox\settingsManager\component;


interface ISettingsQuery
{
    /**
     * @return ISettingsModel[]
     */
    public function all();

    /**
     * @return ISettingsModel|null
     */
    public function one();


    /**
     * @param $name
     *
     * @return $this
     */
    public function byName($name);
}