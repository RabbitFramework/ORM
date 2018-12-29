<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 25/12/2018
 * Time: 18:04
 */

namespace Rabbit\ORM\Mapper\EntityRows;

use Rabbit\ORM\Mapper\EntityInterface;
use Rabbit\ORM\Mapper\MapperInterface;

final class UpdatedEntity implements EntityInterface
{

    use RowEntityTrait;

    /**
     * CreatedEntity constructor.
     * @param Mapper $mapper
     */
    public function __construct(MapperInterface $mapper, $primaryReference)
    {
        $this->driver = $mapper->getDriver();
        $this->builder = $mapper->getBuilder();
        $this->table = $mapper->getTable();
        $this->primaryKeyRef = $primaryReference;
        $this->pull();
    }

    public function saveValue($name)
    {
        if($this->hasValue($name)) {
            $this->driver->add($this->builder->update($this->table->getTableName())->column($name)->values($this->$name)->where("{$this->table->getPrimaryKey()}='".htmlspecialchars(addslashes($this->primaryKeyRef))."'"))->execute();
            $this->driver->closeCursor();
            var_dump($this->builder->update($this->table->getTableName())->column($name)->values($this->$name)->where("{$this->table->getPrimaryKey()}='".htmlspecialchars(addslashes($this->primaryKeyRef))."'")->getQuery());
            return $this->driver->getQuery()->isExecuted;
        }
        return false;
    }

    public function saveAll()
    {
        $query = $this->builder->update($this->table->getTableName());
        foreach ($this->table->getColumnsNames() as $name) {
            if($this->hasValue($name)) {
                $query->column($name)->values($this->$name);
            }
        }
        $this->driver->add($query->where("{$this->table->getPrimaryKey()}='".htmlspecialchars(addslashes($this->primaryKeyRef))."'"))->execute();
        $this->driver->closeCursor();
        return $this->driver->getQuery()->isExecuted;
    }

    public function pull()
    {
        $this->driver->add($this->builder->select('*')->from($this->table->getTableName())->where("{$this->table->getPrimaryKey()}='".htmlspecialchars(addslashes($this->primaryKeyRef))."'"))->execute();
        if($this->driver->getQuery()->isExecuted) {
            foreach ($this->driver->loadAssoc() as $column => $value) {
                $this->$column = $value;
            }
            $this->driver->closeCursor();
        }
    }
}