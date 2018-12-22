<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 21/12/2018
 * Time: 15:30
 */

namespace Rabbit\ORM\Mapper;


use Rabbit\ORM\Builders\Sql;
use Rabbit\ORM\ORM;

/**
 * Class EntityColumn
 * @package Rabbit\ORM\Mapper
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
    public $column;

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
        return $this->column;
    }

    public function set(int $position, string $value) {
        $this->column[$position] = $value;
        return $this;
    }

    public function add(string $value) {
        $this->column[] = $value;
        return $this;
    }

    public function delete(int $position) {
        unset($this->column[$position]);
        return $this;
    }

    public function update() {
        $this->column = $this->get();
        return $this->column;
    }

    public function save() {
        $onlineKeys = $this->get();
        $primaryKey = $this->table->getPrimaryKey()[0];
        foreach (array_keys($onlineKeys) as $getKeys) {
            if(isset($this->column[$getKeys])) { // UPDATE
                if($this->column[$getKeys] !== $onlineKeys[$getKeys]) {
                    $this->driver->add($this->builder->update($this->table->getTableName())->column($this->columnName)->values("'{$this->column[$getKeys]}'")->where("{$primaryKey}={$this->mapper->$primaryKey->column[$getKeys]}"))->execute();
                }
            } else { // DELETE
                $this->driver->add($this->builder->update($this->table->getTableName())->column($this->columnName)->values('null')->where("{$primaryKey}={$this->mapper->$primaryKey->column[$getKeys]}"))->execute();
            }
        }
        foreach (array_diff(array_keys($this->column), array_keys($onlineKeys)) as $diff) { // Add
            $this->driver->add($this->builder->insert($this->table->getTableName())->column($this->columnName)->values(addslashes($this->column[$diff])))->execute();
            if($this->driver->getQuery()->isExecuted) {
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
        return array_search($value, $this->column);
    }

}