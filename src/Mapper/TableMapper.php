<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 25/12/2018
 * Time: 18:06
 */

namespace Rabbit\ORM\Mapper;


use Couchbase\DateRangeSearchFacet;
use Rabbit\ORM\Database;
use Rabbit\ORM\Mapper\EntityColumn\CreatedEntity as ColumnCreatedEntity;
use Rabbit\ORM\Mapper\EntityColumn\UpdatedEntity as ColumnUpdatedEntity;
use Rabbit\ORM\Mapper\EntityRows\CreatedEntity as RowCreatedEntity;
use \Rabbit\ORM\Mapper\EntityRows\UpdatedEntity as RowUpdatedEntity;

/**
 * Class TableMapper
 * @package Rabbit\ORM\Mapper
 */
abstract class TableMapper implements MapperInterface
{

    use MapperTrait;

    /**
     * @var EntityTable
     */
    protected $table;

    /**
     * @var
     */
    protected $rows;

    /**
     * TableMapper constructor.
     * @param string $tableName
     */
    public function __construct(string $tableName)
    {
        $this->driver = Database::getInstance()->getDriver();
        $this->builder = $this->driver->getBuilder();
        $this->table = new EntityTable($this, $tableName ?? strtolower(get_class($this)));
    }

    /**
     *
     */
    public function fetchAllColumns() {
        foreach ($this->table->getColumnsNames() as $name) {
            $this->$name = new ColumnUpdatedEntity($this, $name);
        }
    }

    /**
     * @param string $columnName
     * @return ColumnUpdatedEntity
     */
    public function getColumn(string $columnName) {
        if(isset($this->$columnName)) {
            return $this->$columnName;
        }
        return $this->updateColumn($columnName);
    }

    /**
     * @param string $columnName
     * @return mixed
     */
    public function updateColumn(string $columnName) {
        if($this->tableHasColumn($columnName)) {
            if(!$this->hasColumn($columnName))
                $this->$columnName = new ColumnUpdatedEntity($this, $columnName);
            return $this->$columnName;
        }
    }

    /**
     * @param string $columnName
     * @param string $dataype
     * @return mixed
     */
    public function createColumn(string $columnName, string $dataype) {
        if(!$this->tableHasColumn($columnName)) {
            if(!$this->hasColumn($columnName))
                $this->$columnName = new ColumnCreatedEntity($this, $columnName, $dataype);
            return $this->$columnName;
        }
    }

    /**
     * TODO: Create delete column method
     */
    public function deleteColumn(string $columnName) {
        if($this->hasColumn($columnName)) {
            $this->driver->add()
        }
    }

    /**
     * @param $name
     * @return bool
     */
    public function hasColumn($name) {
        return isset($this->$name) && !empty($this->$name);
    }

    /**
     * @param $name
     * @return bool
     */
    public function tableHasColumn($name) {
        return in_array($name, $this->table->getColumnsNames());
    }

    /**
     *
     */
    public function fetchAllRows() {
        $primaryKey = $this->table->getPrimaryKey();
//        foreach ()
    }

    /**
     * @param $primaryReference
     * @return mixed
     */
    public function updateRow($primaryReference) {
        if(!$this->hasRow($primaryReference)) {
            return $this->rows[$primaryReference] = new RowUpdatedEntity($this, $primaryReference);
        }
        return $this->rows[$primaryReference];
    }

    /**
     * @return RowCreatedEntity
     */
    public function createRow() {
        return $this->rows[] = new RowCreatedEntity($this);
    }

    /**
     * TODO: Create delete row method
     */
    public function deleteRow() {

    }

    /**
     * @param $primaryReference
     * @return bool
     */
    public function hasRow($primaryReference) {
        return isset($this->rows[$primaryReference]) && !empty($this->rows[$primaryReference]);
    }

    /**
     * @param $primaryRefence
     * @return bool
     */
    public function tableHasRow($primaryRefence) {
        $primaryKey = $this->table->getPrimaryKey();
        if(isset($this->$primaryKey) && $this->$primaryKey instanceof ColumnUpdatedEntity)
            return in_array($primaryRefence, $this->$primaryKey->values);
    }

    /**
     * @return EntityTable
     */
    public function getTable()  :EntityTable {
        return $this->table;
    }
}