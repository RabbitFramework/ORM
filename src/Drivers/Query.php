<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 07/01/2019
 * Time: 18:13
 */

namespace Rabbit\ORM\Drivers;


/**
 * Class Query
 * @package Rabbit\ORM\Drivers
 */
class Query
{

    /**
     * @var \PDO
     */
    private $connection;

    /**
     * @var \PDOStatement
     */
    private $queryStatement;

    /**
     * @var
     */
    private $parameters = [];

    /**
     * @var string
     */
    public $queryString;

    /**
     * @var bool
     */
    public $isPrepared = false;

    /**
     * @var bool
     */
    public $isExecuted = false;

    /**
     * Query constructor.
     * @param string $query
     * @param DriverInterface $driver
     */
    public function __construct(string $query, DriverInterface $driver)
    {
        $this->connection = $driver->getConnection();
        $this->queryString = $query;
    }

    /**
     * @return $this
     */
    public function prepare() {
        $this->queryStatement = $this->connection->prepare($this->queryString);
        $this->isPrepared = true;
        return $this;
    }


    /**
     * @param array $additionalParameters
     * @return $this
     */
    public function execute(array $additionalParameters = []) {
        $this->parameters = array_merge($additionalParameters, $this->parameters);
        if(!$this->isPrepared)
            $this->prepare();
        $this->isExecuted = $this->queryStatement->execute($this->parameters);
        return $this;
    }

    /**
     * @return $this|bool
     */
    public function closeCursor() {
        if($this->isExecuted || $this->isPrepared)
            return $this->queryStatement->closeCursor();
        return $this;
    }

    /**
     *
     */
    public function loadObject() {
        if($this->isExecuted) {
            $result = $this->queryStatement->fetch(\PDO::FETCH_OBJ);
            $this->closeCursor();
            return $result;
        }
        return false;
    }

    /**
     *
     */
    public function loadObjects() {
        if($this->isExecuted) {
            $result = $this->queryStatement->fetchAll(\PDO::FETCH_OBJ);
//            var_dump($result);
//            var_dump('Close cursosr: ', $this->closeCursor());
            $this->closeCursor();
            return $result;
        }
        return false;
    }

    /**
     *
     */
    public function loadAssoc() {
        if($this->isExecuted) {
            $result = $this->queryStatement->fetch(\PDO::FETCH_ASSOC);
            $this->closeCursor();
            return $result;
        }
        return false;
    }

    /**
     *
     */
    public function loadAssocs() {
        if($this->isExecuted) {
            $result = $this->queryStatement->fetchAll(\PDO::FETCH_ASSOC);
            $this->closeCursor();
            return $result;
        }
        return false;
    }

    /**
     *
     */
    public function loadColumn() {
        if($this->isExecuted) {
            $result = $this->queryStatement->fetch(\PDO::FETCH_COLUMN);
//            var_dump($result);
//            var_dump('Close cursor: ', $this->closeCursor());
            $this->closeCursor();
            return $result;
        }
        return false;
    }

    /**
     *
     */
    public function loadColumns() {
        if($this->isExecuted) {
            $result = $this->queryStatement->fetchAll(\PDO::FETCH_COLUMN);
            $this->closeCursor();
            return $result;
        }
        return false;
    }

    /**
     *
     */
    public function getId() {
        if($this->isExecuted) {
            return $this->connection->lastInsertId();
        }
        return null;
    }

    /**
     *
     */
    public function getParameter(string $name) {
        if($this->hasParameter($name))
            $this->parameters[$name];
        return $this;
    }

    /**
     *
     */
    public function deleteParameter(string $name) {
        if($this->hasParameter($name)) {
            unset($this->parameters[$name]);
        }
        return $this;
    }


    /**
     * @param string $name
     * @param $value
     * @return $this
     */
    public function addParameter(string $name, $value) {
        if($this->hasParameter($name))
            $this->parameters[$name] = $value;
        return $this;
    }

    /**
     * @param array $parameters
     * @return $this
     */
    public function setParameters(array $parameters) {
        $this->parameters = $parameters;
        return $this;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasParameter(string $name) {
        return isset($this->parameters[$name]);
    }


    /**
     * @param string $query
     * @return $this
     */
    public function setQuery(string $query) {
        $this->queryString = $query;
        $this->isPrepared = false;
        return $this;
    }


    /**
     * @return string
     */
    public function getQuery() {
        return $this->queryString;
    }


    /**
     * @return array
     */
    public function getErrorInfo() {
        if($this->isPrepared)
            return $this->queryStatement->errorInfo();
        return [];
    }

    /**
     * @return string|null
     */
    public function getErrorCode() {
        if($this->isPrepared)
            return $this->queryStatement->errorCode();
        return null;
    }

}