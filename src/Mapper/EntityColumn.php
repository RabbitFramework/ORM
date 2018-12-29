<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 21/12/2018
 * Time: 15:30
 */

namespace Rabbit\ORM\Mapper;


use Rabbit\ORM\Builders\Sql;
use Rabbit\ORM\Database;

/**
 * Class EntityColumn
 * @package Rabbit\Database\Mapper
 */
class EntityColumn
{

    /**
     * @var \Rabbit\ORM\Drivers\BaseDriver
     */
    private $driver;
    /**
     * @var Sql
     */
    private $builder;
    /**
     * @var EntityTable
     */
    private $table;
    /**
     * @var Mapper
     */
    private $mapper;
    /**
     * @var string
     */
    private $columnName;
    /**
     * @var
     */
    public $values;

    public function __construct(Mapper $mapper, string $columnName)
    {
        $this->driver = $mapper->getDriver();
        $this->builder = $mapper->getBuilder();
        $this->table = $mapper->getTable();
        $this->mapper = $mapper;
        $this->columnName = $columnName;

        $this->update();
    }

    public function getValues() {
        return $this->values;
    }

    public function set(int $position, string $value) {
        $this->values[$position] = $value;
        return $this;
    }

    public function add(string $value) {
        $this->values[] = $value;
        return $this;
    }

    public function delete(int $position) {
        unset($this->values[$position]);
        return $this;
    }

    public function update() {
        $this->values = $this->get();
        return $this->values;
    }

    public function save() {
        $onlineKeys = $this->get();
        $primaryKey = $this->table->getPrimaryKey();
        foreach (array_keys($onlineKeys) as $getKeys) {
            if(isset($this->values[$getKeys])) { // UPDATE
                if($this->values[$getKeys] !== $onlineKeys[$getKeys]) {
                    $this->driver->add($this->builder->update($this->table->getTableName())->column($this->columnName)->values("'".htmlspecialchars(addslashes($this->values[$getKeys]))."'")->where("{$primaryKey}={$this->mapper->$primaryKey->values[$getKeys]}"))->execute();
                }
            } else { // DELETE
                $this->driver->add($this->builder->update($this->table->getTableName())->column($this->columnName)->values('null')->where("{$primaryKey}={$this->mapper->$primaryKey->values[$getKeys]}"))->execute();
            }
        }
        foreach (array_diff(array_keys($this->values), array_keys($onlineKeys)) as $diff) { // Add
            var_dump($this->builder->insert($this->table->getTableName())->column($this->columnName)->values(htmlspecialchars(addslashes($this->values[$diff])))->getQuery());
            $this->driver->add($this->builder->insert($this->table->getTableName())->column($this->columnName)->values(htmlspecialchars(addslashes($this->values[$diff]))))->execute();
            if($this->driver->getQuery()->isExecuted) {
                echo 'here';
                $this->driver->add($this->builder->select("max({$primaryKey})")->from($this->table->getTableName()))->execute();
                if($this->driver->getQuery()->isExecuted) {
                    $this->mapper->$primaryKey->column[] = $this->driver->loadColumn();
                }
            }
        }
        $this->update();
    }

    public function get() {
        $this->driver->add($this->builder->select($this->columnName)->from($this->table->getTableName()))->execute();
        if ($this->driver->getQuery()->isExecuted) {
            return $this->driver->loadColumns();
        }
        return [];
    }

    public function getValuePosition(string $value) {
        return array_search($value, $this->values);
    }

}