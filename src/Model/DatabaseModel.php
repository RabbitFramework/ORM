<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 23/12/2018
 * Time: 19:39
 */

namespace Rabbit\ORM\Model;

use Rabbit\MVC\ModelInterface;
use Rabbit\ORM\Database;

if(class_exists('\Rabbit\MVC\Model')) {
    class DatabaseModel implements ModelInterface
    {
        protected $driver;
        protected $builder;

        public function __construct()
        {
            $this->driver = Database::getInstance()->getDriver();
            $this->builder = $this->driver->getBuilder();
        }
    }
}