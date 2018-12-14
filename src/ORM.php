<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 01/12/2018
 * Time: 19:17
 */

namespace Rabbit\ORM;


use Rabbit\File\Drivers\Ini;
use Rabbit\ORM\Drivers\BaseDriver;

class ORM
{

    private $_configPath;

    private $_configParser;

    private $_drivers;

    private $_driver;

    public static $_instance;

    public static function getInstance() {
        if(!isset(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    protected function __construct() {
        $this->_drivers = DriverContainer::getInstance();
    }

    public function setConfigPath(string $path) {
        $this->_configPath = $path;
        return $this;
    }

    public function getDriver() : BaseDriver {
        if(!isset($this->_configParser)) {
            $this->_configParser = new Ini($this->_configPath);
        }

        if(!isset($this->_driver)) {
            $this->_driver = $this->_drivers->get($this->_configParser->getKey('TYPE') ?? 'mysql')->getInstance(['host' => $this->_configParser->getKey('HOST') ?? 'localhost', 'database' => $this->_configParser->getKey('DATABASE') ?? 'default', 'username' => $this->_configParser->getKey('USERNAME') ?? 'root', 'password' => $this->_configParser->getKey('PASSWORD') ?? '']);
        }


        return $this->_driver;
    }

}