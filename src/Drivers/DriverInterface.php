<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 27/11/2018
 * Time: 19:46
 */

namespace Rabbit\ORM\Drivers;

use Rabbit\ORM\Builders\BuilderInterface;

/**
 * Interface DriverInterface
 * @package Rabbit\Database\Drivers
 */
interface DriverInterface
{
    /**
     * @return mixed
     */
    public function getConnection();

    /**
     * @return mixed
     */
    public function closeConnection();

    /**
     * @param array $parameters
     * @return mixed
     */
    public function setConnectionParameters(array $parameters = []);

    /**
     * @return bool
     */
    public function hasConnectionParameters() : bool;

    /**
     * @return bool
     */
    public function hasConnection() : bool;

    /**
     * @return BuilderInterface
     */
    public function getBuilder() : BuilderInterface;

    /**
     * @param string $query
     * @return Query
     */
    public function createQuery(string $query);

    /**
     * @param int|null $id
     * @return Query
     */
    public function getQuery(int $id = 0) : Query;

    /**
     * @param int $id
     * @return bool
     */
    public function hasQuery(int $id = 0) : bool;
}