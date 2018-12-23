<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 21/12/2018
 * Time: 15:34
 */

namespace Rabbit\ORM\Mapper;


use Rabbit\ORM\Builders\Sql;

class EntityRow
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
    private $primaryReference;
    /**
     * @var
     */
    public $values;

    public function __construct(Mapper $mapper, string $primaryReference)
    {
        $this->driver = $mapper->getDriver();
        $this->builder = $mapper->getBuilder();
        $this->table = $mapper->getTable();
        $this->mapper = $mapper;
        $this->primaryReference = $primaryReference;

        $this->update();
    }

    public function update() {
        $this->values = $this->get();
        return $this->values;
    }

    public function save() {
        $onlineValues = $this->get();
        foreach (get_object_vars($onlineValues) as $key => $getKeys) {
            if(isset($this->values->$key)) {
                if($this->values->$key !== $onlineValues->$key) {
                    $this->driver->add($this->builder->update($this->table->getTableName())->column($key)->values("'".htmlspecialchars(addslashes($this->values->$key))."'")->where("{$this->table->getPrimaryKey()}={$this->primaryReference}"))->execute();
                }
            } else {$this->driver->add($this->builder->update($this->table->getTableName())->column($key)->values('null')->where("{$this->table->getPrimaryKey()}={$this->primaryReference}"))->execute();
            }
        }
    }

    public function get() {
        $this->driver->add($this->builder->select('*')->from($this->table->getTableName())->where("{$this->table->getPrimaryKey()}={$this->primaryReference}"))->execute();
        if($this->driver->getQuery()->isExecuted) {
            return $this->driver->loadObjects()[0];
        }
        return [];
    }
}