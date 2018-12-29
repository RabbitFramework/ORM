<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 27/11/2018
 * Time: 19:46
 */

namespace Rabbit\ORM\Drivers;

use  \Rabbit\ORM\Builders\QueryInterface;
use Rabbit\ORM\Queries\Query;

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
     * @param string $parameterName
     * @param $value
     * @return mixed
     */
    public function setConnectionParameter(string $parameterName, $value);

    /**
     * @param array $parameters
     * @return mixed
     */
    public function setConnectionParameters(array $parameters = []);

    /**
     * @param string $name
     * @return mixed
     */
    public function deleteConnectionParameter(string $name);

    /**
     * @param string $name
     * @return bool
     */
    public function hasConnectionParameter(string $name) : bool;

    /**
     * @return bool
     */
    public function hasConnectionParameters() : bool;

    /**
     * @return bool
     */
    public function hasConnection() : bool;

    /**
     * @return QueryInterface
     */
    public function getBuilder() : QueryInterface;

    /**
     * @param string $query
     * @return Query
     */
    public function add(string $query);

    /**
     * @return mixed
     */
    public function getLast();

    /**
     * @return mixed
     */
    public function getLastId();

    /**
     * @param int|null $id
     * @return Query
     */
    public function getQuery(int $id = null) : Query;

    /**
     * @param int $id
     * @return bool
     */
    public function hasQuery(int $id = 0) : bool;

    /**
     * @param int|null $id
     * @return mixed
     */
    public function prepare(int $id = null);

    /**
     * @param int|null $id
     * @return mixed
     */
    public function execute(int $id = null);

    /**
     * @param int|null $id
     * @return mixed
     */
    public function loadObject(int $id = null);

    /**
     * @param int|null $id
     * @return mixed
     */
    public function loadObjects(int $id = null);

    /**
     * @param int|null $id
     * @return mixed
     */
    public function loadAssoc(int $id = null);

    /**
     * @param int|null $id
     * @return mixed
     */
    public function loadAssocs(int $id = null);

    /**
     * @param int|null $id
     * @return mixed
     */
    public function loadColumn(int $id = null);

    /**
     * @param int|null $id
     * @return mixed
     */
    public function loadColumns(int $id = null);

    /**
     * @return self
     */
    public function closeCursor();

}