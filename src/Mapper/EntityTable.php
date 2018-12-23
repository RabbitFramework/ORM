<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 21/12/2018
 * Time: 15:36
 */

namespace Rabbit\ORM\Mapper;


use Rabbit\ORM\ORM;

/**
 * Class EntityTable
 * @package Rabbit\ORM\Mapper
 */
class EntityTable
{
    /**
     * @var \Rabbit\ORM\Drivers\BaseDriver
     */
    private $driver;
    /**
     * @var \Rabbit\ORM\Builders\BaseBuilder|\Rabbit\ORM\Builders\QueryInterface
     */
    private $builder;

    /**
     * @var
     */
    private $table;

    /**
     * EntityTable constructor.
     * @param $table
     */
    public function __construct(Mapper $mapper, $table)
    {
        $this->driver = $mapper->getDriver();
        $this->builder = $mapper->getBuilder();
        $this->table = $table;
    }

    /**
     * @return mixed
     */
    public function getTableName() {
        return $this->table;
    }

    /**
     * @return array|mixed
     */
    public function getColumnsNames() {
        $this->driver->add($this->builder->select('column_name')->from('information_schema.columns')->where('table_name=\''.$this->table.'\''))->execute();
        if($this->driver->getQuery()->isExecuted) {
            $this->columnsNames = $this->driver->loadColumns();
            return $this->columnsNames;
        }
        return [];
    }

    /**
     * @return array|mixed
     */
    public function getColumnsTypes() {
        $this->driver->add($this->builder->select('data_type')->from('information_schema.columns')->where('table_name=\''.$this->table.'\''))->execute();
        if($this->driver->getQuery()->isExecuted) {
            $this->columnsTypes = $this->driver->loadColumns();
            return $this->columnsTypes;
        }
        return [];
    }

    /**
     *
     */
    public function getPrimaryKey()
    {
        $this->driver->add($this->builder->select('column_name')->from('information_schema.columns')->where('table_name=\'' . $this->table . '\'')->andWhere('extra=\'auto_increment\''))->execute();
        if($this->driver->getQuery()->isExecuted) {
            $this->primaryKey = $this->driver->loadColumns();
            return $this->primaryKey;
        }
        return [];
    }

    /**
     * @return bool
     */
    public function hasColumnsNamesSet() {
        return isset($this->columnsNames);
    }

    /**
     * @return bool
     */
    public function hasColumnsTypesSet() {
        return isset($this->columnsTypes);
    }

    /**
     * @return bool
     */
    public function hasPrimaryKeySet() {
        return isset($this->primaryKey);
    }

}