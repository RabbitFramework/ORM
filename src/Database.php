<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 12/01/2019
 * Time: 22:03
 */

namespace Rabbit\ORM;

use Rabbit\ORM\Drivers\DriverInterface;

class Database
{

    private $config;

    private $driverContainer;

    private $driver;

    /**
     * @var
     */
    public static $_instance;

    /**
     * @return Database
     */
    public static function getInstance() {
        if(!isset(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct()
    {
        $this->config = DatabaseConfigContainer::getInstance();
        $this->driverContainer = DriverContainer::getInstance();
    }

    public function getDriver() : DriverInterface {

        if(!isset($this->_driver)) {
            $this->driver = $this->driverContainer->get($this->config->getDbType() ?? 'mysql')->getInstance(['host' => $this->config->getDbHost() ?? 'localhost', 'database' => $this->config->getDbName() ?? 'default', 'username' => $this->config->getDbUser() ?? 'root', 'password' => $this->config->getDbPassword() ?? '', 'charset' => $this->config->getDbCharset() ?? 'utf8', 'attributes' => $this->config->getDbAttributes(), 'attributesValues' => $this->config->getDbAttributesValues()]);
        }

        return $this->driver;
    }

}