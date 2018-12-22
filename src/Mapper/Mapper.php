<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 21/12/2018
 * Time: 14:25
 */

namespace Rabbit\ORM\Mapper;

use Rabbit\ORM\Builders\Sql;
use Rabbit\ORM\Drivers\DriverInterface;
use Rabbit\ORM\ORM;

/**
 * Class Mapper
 * @package Rabbit\ORM\Mapper
 */
class Mapper
{

    /**
     * @var DriverInterface
     */
    protected $driver;
    protected $builder;
    protected $table;

    /**
     * Mapper constructor.
     * @param string|null $table
     */
    public function __construct(string $table = null)
    {
        $this->driver = ORM::getInstance()->getDriver();

        $this->builder = $this->driver->getBuilder();

        $this->table = new EntityTable($this, $table ?? strtolower(get_class($this)));

        $this->getAllColumnsEntities();
    }

    public function saveColumn(string $name) {
        if(isset($this->$name) && $this->$name instanceof EntityColumn) {
            $this->$name->save();
            $this->getAllColumnsEntities();
            return true;
        }
        return false;
    }

    public function saveColumns(string ...$names) {
        foreach ($names as $name) {
            $this->saveColumn($name);
        }
        return true;
    }

    public function saveAllColumns() {
        foreach ($this->table->getColumnsNames() as $name) {
            $this->saveColumn($name);
        }
        return true;
    }

    /**
     *
     */
    public function getColumnEntity(string $name) {
        if(in_array($name, $this->table->getColumnsNames())) {
            if(!isset($this->$name)) {
                $this->$name = new EntityColumn($this, $name);
            }
            return $this->$name;
        }
        return null;
    }

    public function getColumnsEntities(string ...$names) {
        $fields = [];
        foreach ($names as $name) {
            $fields[] = $this->getColumnEntity($name);
        }
        return $fields;
    }

    public function getAllColumnsEntities() {
        $fields = [];
        foreach ($this->table->getColumnsNames() as $name) {
            $fields[] = $this->getColumnEntity($name);
        }
        return $fields;
    }

    /**
     * @return DriverInterface
     */
    public function getDriver(): DriverInterface
    {
        return $this->driver;
    }

    /**
     * @return \Rabbit\ORM\Builders\QueryInterface
     */
    public function getBuilder()
    {
        return $this->builder;
    }

    /**
     * @return EntityTable
     */
    public function getTable(): EntityTable
    {
        return $this->table;
    }

}