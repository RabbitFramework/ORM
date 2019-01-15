<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 01/12/2018
 * Time: 19:20
 */

namespace Rabbit\ORM;

use Rabbit\DependencyInjector\Container;
use Rabbit\ORM\Drivers\MySqlDriver;

class DriverContainer extends Container
{

    public static $_instance;

    public static function getInstance() {
        if(!isset(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {
        $this->addAlias(MySqlDriver::class, 'mysql', 'MySql', 'MYSQL');
    }

}