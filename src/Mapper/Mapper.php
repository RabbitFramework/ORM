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
use Rabbit\ORM\Database;

/**
 * Class Mapper
 * @package Rabbit\Database\Mapper
 */
class Mapper
{

    /**
     * @var DriverInterface
     */
    protected $driver;
    protected $builder;
    protected $table;
    public $rows;

    /**
     * Mapper constructor.
     * @param string|null $table
     */
    public function __construct(string $table = null)
    {
        $this->driver = Database::getInstance()->getDriver();

        $this->builder = $this->driver->getBuilder();

        $this->table = new EntityTable($this, $table ?? strtolower(get_class($this)));

        $this->getAllColumnsEntities();
        $this->getAllRowsEntities();
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

    public function saveRow($primaryReference) {
        if(isset($this->rows[$primaryReference]) && $this->rows[$primaryReference] instanceof EntityRow) {
            $this->rows[$primaryReference]->save();
            $this->getAllRowsEntities();
            return true;
        }
        return false;
    }

    public function saveRows(...$primaryReference) {
        foreach ($primaryReference as $name) {
            $this->saveRow($name);
        }
        return true;
    }

    public function saveAllRows() {
        $primaryKey = $this->table->getPrimaryKey();
        foreach ($this->$primaryKey->values as $name) {
            $this->saveRow($name);
        }
        return true;
    }

    public function getRowEntity($primaryReference) {
        $primaryKey = $this->table->getPrimaryKey();
        if(in_array($primaryReference, $this->$primaryKey->values)) {
            if(!isset($this->rows[$primaryReference])) {
                $this->rows[$primaryReference] = new EntityRow($this, $primaryReference);
            }
            return $this->rows[$primaryReference];
        }
        return null;
    }

    public function getRowsEntities(...$primaryReferences) {
        $rows = [];
        foreach ($primaryReferences as $primaryReference) {
            $rows[] = $this->getRowEntity($primaryReference);
        }
        return $rows;
    }

    public function getAllRowsEntities() {
        $fields = [];
        $primaryKey = $this->table->getPrimaryKey();
        foreach ($this->$primaryKey->values as $primaryReference) {
            $fields[] = $this->getRowEntity($primaryReference);
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