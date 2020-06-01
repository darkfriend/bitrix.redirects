<?php
/**
 * Created by PhpStorm.
 * User: darkfriend <hi@darkfriend.ru>
 * Date: 11.01.2018
 * Time: 22:24
 */

namespace Dev2fun\Redirects;


use Bitrix\Main\Config\Option;

class Config
{
    protected static $moduleId = 'dev2fun.redirects';
    private $options;
    private static $instance;

    /**
     * Singleton instance.
     * @return self
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
            self::$instance->init();
        }
        return self::$instance;
    }

    public function init()
    {
        $this->options = Option::getForModule(self::$moduleId);
    }

    public function get($name)
    {
        if(empty($this->options)) {
            $this->init();
        }
        return $this->options[$name];
    }

    public function set($name, $value)
    {
        $this->options[$name] = $value;
    }

    public function setAll($arOption)
    {
        $this->options = \array_merge(
            $this->options,
            $arOption
        );
    }
}