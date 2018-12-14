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
     * Sql
     */
        /**
         * Sql => SAVE
         */
    public function saveAll() {
    }

    public function saveField(string $fieldName) {
        if($this->hasFieldName($fieldName)) {
            foreach ($this->$fieldName as $key => $value) {
                $primary = $this->primaryKey;
                if(!isset($this->$primary[$key])) {
                    $this->$primary[] = $key+1;
                }
//                var_dump($this->builder->update()->table($this->table)->set()->columns($fieldName)->values($value)->where($this->primaryKey.'='.$this->$primary[$key])->getSql());
                $this->driver->add($this->builder->update()->table($this->table)->set()->columns($fieldName)->values("'{$value}'")->where($this->primaryKey.'='.$this->$primary[$key]))->execute();
                if($this->driver->getQuery()->isExecuted) {
                    echo 'hrre';
                }
//                var_dump($value);
            }
        }
    }

        /**
         * Sql => CREATE
         */
    public function createField(string $name, string $type) {
        $this->driver->add($this->builder->alter()->table($this->table)->create()->column($name)->dataType($type))->execute();
        if($this->driver->getQuery()->isExecuted()) {
            $this->getFieldsNames();
            $this->$name = [];
        }
    }

    public function createFields() {

    }

        /**
         * Sql => Drop
         */
    public function dropField(string $name) {

    }
        /**
         * Sql => Get
         */
    public function getFields() {
        $fields = [];
        foreach ($this->fieldsNames as $name) {
            $fields[] = $this->getField($name);
        }
        return $fields;
    }

    public function getField(string $name) {
        if(!$this->hasFieldsNames()) $this->getFieldsNames();
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

    public function getRow(int $id) {
        if($this->hasPrimaryKey()) {
            $this->driver->add($this->builder->select('*')->from($this->table)->where($this->primaryKey.'='.$id))->execute();
            if($this->driver->getQuery()->isExecuted()) {
                return $this->driver->loadAssocs()[0];
            }
        }
        return null;
    }

    public function getRows(int ...$ids) {
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
     * Variables
     */
        /**
         * Variables => set
         */
    public function getFieldsNames() {
        $this->driver->add($this->builder->select('column_name')->from('information_schema.columns')->where('table_name=\''.$this->table.'\''))->execute();
        if($this->driver->getQuery()->isExecuted()) {
            $this->fieldsNames = $this->driver->loadColumns();
        }
        return $this;
    }

    public function setTable(string $name = null) {
        if(isset($name)) {
            $this->table = $name;
        }
        $this->table = strtolower(get_class($this));
        return $this;
    }

    public function setPrimaryKey(string $primaryName) {
        if(!$this->hasFieldsNames()) $this->getFieldsNames();
        if($this->hasFieldName($primaryName)) $this->primaryKey = $primaryName;
        return $this;
    }

        /**
         * Variables => has
         */
    public function hasFieldName(string $name) {
        return in_array($name, $this->fieldsNames);
    }

    public function hasFieldsNames() {
        return !empty($this->fieldsNames);
    }

    public function hasPrimaryKey() {
        return !empty($this->primaryKey);
    }
}