<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 25/12/2018
 * Time: 18:03
 */

namespace Rabbit\ORM\Mapper\EntityRows;

use Rabbit\ORM\Mapper\EntityInterface;
use Rabbit\ORM\Mapper\MapperInterface;

/**
 * Class CreatedEntity
 * @package Rabbit\ORM\Mapper\EntityRows
 */
final class CreatedEntity implements EntityInterface
{
    use RowEntityTrait;

    public $primaryKey = null;

    public $primaryName = '';

    public $values = [];

    /**
     * CreatedEntity constructor.
     * @param Mapper $mapper
     */
    public function __construct(MapperInterface $mapper)
    {
        $this->driver = $mapper->getDriver();
        $this->builder = $mapper->getBuilder();
        $this->table = $mapper->getTable();
        $this->primaryName = $this->table->getPrimaryKey();
        $this->pull();
    }

    /**
     * @param string $name
     */
    public function saveValue($name)
    {
        if($this->$name) {
            if(empty($this->primaryKey)) {
                $this->driver->add($this->builder->insert($this->table->getTableName())->column($name)->values($this->$name))->execute();
                if($this->driver->getQuery()->isExecuted) {
                    $this->driver->closeCursor();
                    $this->pullPrimary();
                }
            } else {
                $this->driver->add($this->builder->update($this->table->getTableName())->column($name)->values($this->$name)->where("{$this->table->getPrimaryKey()}='".htmlspecialchars(addslashes($this->primaryKey))."'"))->execute();
                $this->driver->closeCursor();
                return $this->driver->getQuery()->isExecuted;
            }
        }
        return false;
    }

    /**
     * @return bool
     */
    public function saveAll()
    {
        foreach ($this->values as $name => $value) {
            $this->saveValue($name);
        }
    }

    public function pullPrimary()
    {
        $this->driver->add($this->builder->select("max({$this->table->getPrimaryKey()})")->from($this->table->getTableName()))->execute();
        if($this->driver->getQuery()->isExecuted) {
            $this->primaryKey  = $this->driver->loadColumn();
            $this->driver->closeCursor();
            return true;
        }
        return false;
    }

    /**
     * @return $this
     */
    public function pull()
    {
        foreach ($this->table->getColumnsNames() as $name) {
            $this->$name = '';
        }
        return $this;
    }

}