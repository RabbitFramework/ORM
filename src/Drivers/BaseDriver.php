<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 01/12/2018
 * Time: 19:16
 */

namespace Rabbit\ORM\Drivers;

use Rabbit\ORM\Builders\BuilderInterface;
use \Rabbit\ORM\Drivers\Query;

/**
 * Class BaseDriver
 * @package Rabbit\Database\Drivers
 */
abstract class BaseDriver implements DriverInterface
{
    /**
     * @var \PDO
     */
    protected $_connection;

    /**
     * @var
     */
    protected $_connectionParameters;

    /**
     * @var
     */
    protected $queries = [];

    /**
     * @var int
     */
    public $_currentQuery = 0;

    /**
     * BaseDriver constructor.
     * @param array $parameters
     */
    public function __construct(array $parameters) {
        $this->setConnectionParameters($parameters);
    }

    /**
     * @return mixed
     */
    abstract public function getConnection();

    /**
     * @return BuilderInterface
     */
    abstract public function getBuilder() : BuilderInterface;

    /**
     *
     */
    public function closeConnection() {
        if($this->hasConnection()) {
            $this->_connection = null;
        }
    }

    /**
     * @param string $parameterName
     * @param $value
     * @return $this
     */
    public function setConnectionParameter(string $parameterName, $value)
    {
        $this->_connectionParameters[$parameterName] = $value;
        return $this;
    }

    /**
     * @param array $parameters
     * @return $this
     */
    public function setConnectionParameters(array $parameters = []) {
        $this->_connectionParameters = $parameters;
        return $this;
    }

    /**
     * @param string $name
     */
    public function deleteConnectionParameter(string $name) {
        if($this->hasConnectionParameter($name)) {
            unset($this->_connectionParameters[$name]);
        }
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasConnectionParameter(string $name) : bool {
        return isset($this->_connectionParameters[$name]);
    }

    /**
     * @return bool
     */
    public function hasConnectionParameters() : bool {
        return isset($this->_connectionParameters);
    }

    /**
     * @return bool
     */
    public function hasConnection() : bool {
        return isset($this->_connection);
    }

    /**
     * @param string $query
     * @return self
     */
    public function createQuery(string $query) {
        $query = new Query($query, $this);
        $this->queries[] = $query;
        $this->_currentQuery = array_search($query, $this->queries);
        return $this->queries[$this->_currentQuery];
    }

    /**
     * @param int|null $query
     * @return mixed
     */
    public function getQuery(int $id = null) : Query {
        if($this->hasQuery($id ?? $this->_currentQuery)) {
            return $this->queries[$id ?? $this->_currentQuery];
        }
    }

    /**
     * @param int $query
     * @return bool
     */
    public function hasQuery(int $id = 0) : bool {
        return isset($this->queries[$id]);
    }

    abstract public function getErrorCode();

    abstract public function getErrorInfo();
}