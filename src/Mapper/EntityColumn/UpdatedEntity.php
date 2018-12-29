<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 25/12/2018
 * Time: 18:04
 */

namespace Rabbit\ORM\Mapper\EntityColumn;

use Rabbit\ORM\Mapper\EntityInterface;
use Rabbit\ORM\Mapper\MapperInterface;

final class UpdatedEntity implements EntityInterface
{

    use ColumnEntityTrait;

    public $primaryKeys = [];

    public $primaryName = '';

    public function __construct(MapperInterface $mapper, string $columnName)
    {
        $this->driver = $mapper->getDriver();
        $this->builder = $mapper->getBuilder();
        $this->table = $mapper->getTable();
        $this->primaryName = $this->table->getPrimaryKey();
        $this->columnName = $columnName;
        $this->pull();
    }

    public function saveValue($primaryRef) {
        if(in_array($primaryRef, $this->primaryKeys)) {
            if(isset($this->values[$primaryRef])) {
                $this->driver->add($this->builder->update($this->table->getTableName())->column($this->columnName)->values(htmlspecialchars(addslashes($this->values[$primaryRef])))->where("{$this->primaryName}=".htmlspecialchars(addslashes($this->primaryKeys[array_search($primaryRef, $this->primaryKeys)]))));
            } else {
                $this->driver->add($this->builder->update($this->table->getTableName())->column($this->columnName)->values('')->where("{$this->primaryName}=:where"))->setParameters([':where' => $this->primaryKeys[array_search($primaryRef, $this->primaryKeys)]]);
            }
        } else {
            $this->driver->add($this->builder->insert($this->table->getTableName())->column($this->columnName)->values(htmlspecialchars(addslashes($this->values[$primaryRef]))));
        }
        $this->driver->execute();
        $this->driver->closeCursor();
        $this->pullPrimary();
        return $this->driver->getQuery()->isExecuted();
    }

    public function saveAll()
    {
        foreach ($this->values as $id => $key) {
            $this->saveValue($id);
        }
    }

    public function pullPrimary() {
        $this->driver->add($this->builder->select($this->primaryName)->from($this->table->getTableName()))->execute();
        if($this->driver->getQuery()->isExecuted) {
            $this->primaryKeys = $this->driver->loadColumns();
            $this->driver->closeCursor();
            return true;
        }
        return false;
    }

    public function pull()
    {
        $this->driver->add($this->builder->select($this->primaryName)->andSelect($this->columnName)->from($this->table->getTableName()))->execute();
        if($this->driver->getQuery()->isExecuted()) {
            foreach ($this->driver->loadAssocs() as $assoc) {
                $this->primaryKeys[] = $assoc[$this->primaryName];
                $this->values[$assoc[$this->primaryName]] = $assoc[$this->columnName];
            }
            $this->driver->closeCursor();
            return true;
        }
        return false;
    }

}