<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 08/12/2018
 * Time: 07:20
 */

namespace Rabbit\ORM\Queries;

use Rabbit\ORM\Database;
use Rabbit\ORM\Drivers\DriverInterface;

/**
 * Class Query
 * @package Rabbit\Database\Queries
 */
class Query
{
    /**
     * The query
     *
     * @var string
     */
    public $query = '';

    private $connection;

    /**
     * The prepared query
     *
     * @var \PDOStatement
     */
    public $preparedQuery;

    /**
     * The parameters (!) Warning, it's different from values
     *
     * @var array
     */
    public $parameters = [];

    /**
     * The values (!) Warning, it's different from parameters
     *
     * @var array
     */
    public $values = [];

    /**
     * Parameter alias, default it's ':'
     *
     * @var string
     */
    public $parameterAlias = ':';

    /**
     * Value alias, default it's ':'
     *
     * @var string
     */
    public $valueAlias = ':';

    /**
     * To check if the query was executed
     *
     * @var bool
     */
    public $isExecuted = false;

    /**
     * @var bool
     */
    public $isPrepared = false;

    /**
     * PDOQuery constructor.
     * @param string $query
     */
    public function __construct(string $query = '', DriverInterface $db) {
        $this->query = $query;
        $this->connection = $db->getConnection();
    }

    public function prepare(array $parameters = []) {
        $this->preparedQuery = $this->connection->prepare($this->query);
        foreach (array_merge($this->parameters, $parameters) as $name => $parameter) {
            $parameterValue = $parameter['value'] ?? $parameter;
            $parameterType = $parameter['type'] ?? \PDO::PARAM_STR;
            $this->preparedQuery->bindParam($name, $parameterValue, $parameterType);
        }
        return $this;
    }

    public function execute(array $parameters = []) {
        if(!$this->isPrepared) {
            $this->prepare($parameters);
        }
        $this->isExecuted = $this->preparedQuery->execute();
        return $this;
    }

    /**
     * This method is used to set the query instead of pass by constructor.
     * The parameter is a string $query.
     * The return value is $this.
     *
     * @param string $query
     * @return $this
     */
    public function setQuery(string $query = '') {
        $this->query = $query;
        return $this;
    }

    /**
     * This method is used to return the query seted in the constructor or in the method Query::setQuery($query)
     *
     * @return string
     */
    public function getQuery() {
        return $this->query;
    }

    /**
     * @param $preparedQuery
     * @return $this
     */
    public function setPreparedQuery($preparedQuery) {
        $this->preparedQuery = $preparedQuery;
        return $this;
    }

    /**
     * @return \PDOStatement
     */
    public function getPreparedQuery() {
        return $this->preparedQuery;
    }

    /* ~~ Value/Parameters seter-geter-has ~~ */

    /**
     * Use to add/replace all parameters in a array of type ['name' => ['value' => $value, 'type' => $type]] or ['name' => 'value']
     *
     * @param array $parameters
     */
    public function setParameters(array $parameters) {
        $this->parameters = $parameters;
        return $this;
    }

    /**
     * Use to delete a parameter or multiple parameters (cause of variadic variables used in parameters)
     *
     * @param string ...$name
     * @return $this
     */
    public function deleteParameter(string ...$name) {
        foreach ($name as $value) {
            if($this->hasParameter($value)) {
                unset($this->parameters[$value]);
            }
        }
        return $this;
    }

    /**
     * Use to get a single parameter by name
     *
     * @param string $name
     * @return bool|mixed
     */
    public function getParameter(string $name) {
        if($this->hasParameter($name)) return $this->parameters[$name];
        return false;
    }

    /**
     * Use to get all parameters
     *
     * @return array
     */
    public function getParameters() {
        return $this->parameters;
    }

    /**
     * Use to check if the class has the parameter
     *
     * @param string $name
     * @return bool
     */
    public function hasParameter(string $name) {
        return isset($this->parameters[$name]);
    }

    /**
     * Use to set the parameter alias
     *
     * @param string $alias
     * @return $this
     */
    public function setParameterAlias(string $alias) {
        $this->parameterAlias = $alias;
        return $this;
    }

    /**
     * Use to get the parameter alias
     *
     * @param string $alias
     * @return string
     */
    public function getParameterAlias(string $alias) {
        return $this->parameterAlias;
    }

    /**
     * Use to set a single parameter by name
     *
     * @param string $name
     * @param $value
     * @return $this
     */
    public function setValue(string $name, $value) {
        $this->values[$name] = $value;
        return $this;
    }

    /**
     * @param array $values
     */
    public function setValues(array $values) {
        $this->values = array_replace($this->values, $values);
    }

    /**
     * @param string ...$name
     * @return $this
     */
    public function deleteValue(string ...$name) {
        foreach ($name as $value) {
            if($this->hasValue($value)) {
                unset($this->values[$value]);
            }
        }
        return $this;
    }

    /**
     * @param string $name
     * @return bool|mixed
     */
    public function getValue(string $name) {
        if($this->hasValue($name)) return $this->values[$name];
        return false;
    }

    /**
     * @return array
     */
    public function getValues() {
        return $this->values;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasValue(string $name) {
        return isset($this->values[$name]);
    }

    /**
     * @param string $alias
     * @return $this
     */
    public function setValueAlias(string $alias) {
        $this->valueAlias = $alias;
        return $this;
    }

    /**
     * @return string
     */
    public function getValueAlias() {
        return $this->valueAlias;
    }

    /**
 * @return bool
 */
    public function isExecuted(): bool
    {
        return $this->isExecuted;
    }

    /**
     * @param bool $isExecuted
     * @return $this
     */
    public function setExecuted(bool $isExecuted)
    {
        $this->isExecuted = $isExecuted;
        return $this;
    }

    /**
     * @return bool
     */
    public function isPrepared() {
        return $this->isPrepared;
    }

    /**
     * @param bool $isPrepared
     *
     * @return $this;
     */
    public function setPrepared(bool $isPrepared) {
        $this->isPrepared = $isPrepared;
        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->query;
    }
}