<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 28/12/2018
 * Time: 18:07
 */

namespace Rabbit\ORM\Mapper;


use Rabbit\ORM\Builders\QueryInterface;
use Rabbit\ORM\Drivers\DriverInterface;

trait MapperTrait
{

    protected $driver;

    protected $builder;

    public function getDriver() : DriverInterface {
        return $this->driver;
    }

    public function getBuilder() : QueryInterface {
        return $this->builder;
    }

}