<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 26/12/2018
 * Time: 10:01
 */

namespace Rabbit\ORM\Mapper;


use Rabbit\ORM\Builders\QueryInterface;
use Rabbit\ORM\Drivers\DriverInterface;

interface MapperInterface
{

    public function getDriver() : DriverInterface;

    public function getBuilder() : QueryInterface;

}