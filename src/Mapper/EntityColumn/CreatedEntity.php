<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 25/12/2018
 * Time: 18:03
 */

namespace Rabbit\ORM\Mapper\EntityColumn;

use Rabbit\ORM\Mapper\EntityInterface;
use Rabbit\ORM\Mapper\MapperInterface;

final class CreatedEntity implements EntityInterface
{
    use ColumnEntityTrait;

    protected $datatype;

    public $primaryKeys = [];

    public $primaryName = '';

    public function __construct(MapperInterface $mapper, string $columnName, string $datatype)
    {
        $this->driver = $mapper->getDriver();
        $this->builder = $mapper->getBuilder();
        $this->table = $mapper->getTable();
        $this->primaryName = $this->table->getPrimaryKey();
        $this->columnName = $columnName;
        $this->datatype = $datatype;
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
            $this->driver->add($this->builder->insert($this->table->getTableName())->column($this->columnName)->column($this->columnName)->values(':value'))->setParameters([':value' => $this->values[$primaryRef]]);
        }
        $this->driver->execute();
        $this->pullPrimary();
        return $this->driver->closeCursor()->getQuery()->isExecuted();
    }

    public function saveAll()
    {
        $multi = $this->builder->createMulti();
        foreach ($this->primaryKeys as $key) {
            if(isset($this->values[$key])) { // UPDATE
                $multi->add($this->builder->update($this->table->getTableName())->column($this->columnName)->values(htmlspecialchars(addslashes($this->values[$key])))->where("{$this->primaryName}={$key}"));
            } else { // DELETE
                $multi->add($this->builder->update($this->table->getTableName())->column($this->columnName)->values("''")->where("{$this->primaryName}={$key}"));
            }
        }
        foreach (array_diff(array_keys($this->values), $this->primaryKeys) as $diff) { // INSERT
            $multi->add($this->builder->insert($this->table->getTableName())->column($this->columnName)->values(htmlspecialchars(addslashes($this->values[$diff]))));
        }
        $this->driver->add($multi)->execute();
        $this->pullPrimary();
        return $this->driver->closeCursor()->getQuery()->isExecuted();
    }

    public function pullPrimary() {
        $this->driver->add($this->builder->select($this->primaryName)->from($this->table->getTableName()));
        if($this->driver->getQuery()->isExecuted) {
            $this->primaryKeys = $this->driver->loadColumns();
            $this->driver->closeCursor();
            return true;
        }
        return false;
    }

    public function pull()
    {
        $multi = $this->builder->createMulti()->add($this->builder->select($this->primaryName)->from($this->table->getTableName()), $this->builder->createColumn($this->table->getTableName())->column($this->columnName)->datatype($this->datatype));
        $this->driver->add($multi)->execute();
        if($this->driver->getQuery()->isExecuted) {
            foreach ($this->driver->loadColumns() as $key => $primaryRef) {
                $this->primaryKeys[] = $primaryRef;
                $this->values[$primaryRef] = '';
            }
            $this->driver->closeCursor();
            return true;
        } else {echo 'coucou existe déjà';}
        return false;
    }
}