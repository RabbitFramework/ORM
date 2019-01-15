<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 06/01/2019
 * Time: 14:02
 */

namespace Rabbit\ORM\Repository;

use Rabbit\ORM\Drivers\DriverInterface;

class Container
{

    private $driver;

    protected function __construct(DriverInterface $driver)
    {
        $this->driver = $driver;
    }

    public function getDriver() {
        return $this->driver;
    }

}