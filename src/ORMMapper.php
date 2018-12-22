<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 21/11/2018
 * Time: 16:45
 */

namespace Rabbit\ORM;

/**
 * Class ORMMapper
 * @package Rabbit\ORM
 */
class ORMMapper
{

    protected $driver;
    protected $builder;
    protected $table = '';
    protected $primaryKey = '';

    protected $fieldsNames = [];

    public function __construct(string $table = null)
    {
        $this->driver = ORM::getInstance()->getDriver();

        $this->builder = $this->driver->getBuilder();

        $this->setTable($table);
    }

    /**
     * Fields
     */
        /**
         * Fields => save single field
         */
    public function saveField(string $name) {
        if($this->hasFieldName($name)) {
            foreach ($this->$name as $key => $value) {
                $primary = $this->primaryKey;
                if(!isset($this->$primary[$key])) {
                    $this->$primary[] = $key+1;
                }
                $this->driver->add($this->builder->update()->table($this->table)->set()->columns($name)->values("'{$value}'")->where($this->primaryKey.'='.$this->$primary[$key]))->execute();
            }
            return true;
        }
        return false;
    }
        /**
         * Fields => save multiple fields
         */
    public function saveFields(string ...$names) {
        foreach ($names as $name) {
            $this->saveField($name);
        }
        return true;
    }
        /**
         * Fields => save all fields
         */
    public function saveAllFields() {
        foreach ($this->getFieldsNames() as $name) {
            $this->saveField($name);
        }
        return true;
    }
        /**
         * Fields => create
         */
    public function createField(string $name) {
        if(!$this->hasFieldName($name)) {
            $this->driver->add($this->builder->alter()->table($this->table)->create()->column($name)->dataType($type))->execute();
            if($this->driver->getQuery()->isExecuted()) {
                $this->getFieldsNames();
                $this->$name = [];
            }
        }
    }
        /**
         * Fields => create multiple fields
         */
    public function createFields(string ...$names) {
        foreach ($names as $name) {
            $this->createField($name);
        }
    }

        /**
         * Fields => drop single fields
         */
    public function dropField(string $name) {

    }
        /**
         * Fields => drop multiple fields
         */
    public function dropFields(string ...$names) {

    }
        /**
         * Fields => get single field CHECKED
         */
    public function getField(string $name) {
        if(!$this->hasFieldsNamesSet()) $this->getFieldsNames();
        if($this->hasFieldName($name)) {
            $this->driver->add($this->builder->select($name)->from($this->table))->execute();
            if($this->driver->getQuery()->isExecuted()) {
                foreach ($this->driver->loadAssocs() as $assoc) {
                    $this->$name[] = $assoc[$name];
                }
            }
        }
        return $this->$name;
    }
        /**
         * Fields => get multiple fields CHECKED
         */
    public function getFields(string ...$names) {
        if(!$this->hasFieldsNamesSet()) $this->getFieldsNames();
        $fields = [];
        foreach ($names as $name) {
            $fields[] = $this->getField($name);
        }
        return $fields;
    }
        /**
         * Fields => get all fields CHECKED
         */
    public function getAllFields() {
        if(!$this->hasFieldsNamesSet()) $this->getFieldsNames();
        $fields = [];
        foreach ($this->fieldsNames as $name) {
            $fields[] = $this->getField($name);
        }
        return $fields;
    }

    /**
     * Rows
     */
    public function createRow(...$values) {
        if(!$this->hasFieldsNamesSet()) $this->getAllFields();
        $this->builder->insert($this->table);
        foreach ($values as $id => $value) {
            $this->builder->column($this->fieldsNames[$id])->value();
        }
    }

    public function createRows(array ...$values) {

    }

    public function deleteRow(int $id) {

    }

    public function deleteRows(int ...$ids) {

    }
        /**
         * Rows => get single
         */
    public function getRow(int $id) {
        if($this->hasFieldsNamesSet()) $this->getFieldsNames();
        if($this->hasPrimaryKey()) {
            $this->driver->add($this->builder->select('*')->from($this->table)->where($this->primaryKey.'='.$id))->execute();
            if($this->driver->getQuery()->isExecuted()) {
                $result = $this->driver->loadAssocs();
                if(!empty($result)) {
                    return $result[0];
                }
            }
        }
        return [];
    }

        /**
         * Rows => get multiple
         */
    public function getRows(int ...$ids) {
        if($this->hasFieldsNamesSet()) $this->getFieldsNames();
        if($this->hasPrimaryKey()) {
            $rows = [];
            foreach ($ids as $id) {
                $rows[] = $this->getRow($id);
            }
            return $rows;
        }
        return null;
    }

        /**
         * Rows => get all
         */
    public function getAllRows() {
        if($this->hasFieldsNamesSet()) $this->getFieldsNames();
        if($this->hasPrimaryKey()) {
            $rows = [];
            $key = $this->primaryKey;
            foreach ($this->$key as $id) {
                $rows[] = $this->getRow($id);
            }
            return $rows;
        }
        return null;
    }

    /**
     * Variables
     */
        /**
         * Variables => set CHECKED
         */
    public function getFieldsNames() {
        $this->driver->add($this->builder->select('column_name')->from('information_schema.columns')->where('table_name=\''.$this->table.'\''))->execute();
        if($this->driver->getQuery()->isExecuted()) {
            $this->fieldsNames = $this->driver->loadColumns();
        }
        return $this->fieldsNames;
    }

        /**
         * Variables => has single field name CHECKED
         */
    public function hasFieldName(string $name) {
        return in_array($name, $this->fieldsNames);
    }

        /**
         * Variables => has fields names set CHECKED
         */
    public function hasFieldsNamesSet() {
        return !empty($this->fieldsNames);
    }

        /**
         * Variables => set primary key CHECKED
         */
    public function setPrimaryKey(string $primaryName) {
        if(!$this->hasFieldsNamesSet()) $this->getFieldsNames();
        if($this->hasFieldName($primaryName)) $this->primaryKey = $primaryName;
        return $this;
    }
        /**
         * Variables => has primary key CHECKED
         */
    public function hasPrimaryKey() {
        return !empty($this->primaryKey);
    }
        /**
         * Variables => set table CHECKED
         */
    public function setTable(string $name = null) {
        if(isset($name)) {
            $this->table = $name;
        } else {
            $this->table = strtolower(get_class($this));
        }
        return $this;
    }
        /**
         * Variables => get table CHECKED
         */
    public function getTable() {
        return $this->table;
    }
}