<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 04/01/2019
 * Time: 19:39
 */

namespace Rabbit\ORM\Repository;

use Rabbit\ORM\Builders\Sql;
use Rabbit\ORM\Drivers\DriverInterface;

class Repository implements RepositoryInterface
{

    protected $table;

    protected $primaryName;

    protected $entityName;

    protected $driver;

    protected $manager;

    public function __construct(EntityManagerInterface $manager, DriverInterface $driver, string $tableName = null)
    {
        $this->driver = $driver;
        $this->manager = $manager;
        $this->table = new TableData($driver, $tableName ?? str_ireplace('repository', '', get_class($this)));
        $this->primaryName = $this->table->getPrimaryName();
        $this->entityName = $manager->getEntityName(get_class($this));
    }

    public function findById($id) : Entity
    {
        $entity = new Entity();
        foreach ($this->driver->createQuery(Sql::select('*')->from($this->table->getName())->where("{$this->primaryName}=:id"))->execute([':id' => $id])->loadAssoc() as $item => $value) {
            $entity->$item = $value;
        }
        return $entity;
    }

    public function findOneByField(string $name, $value) {
        return $this->createEntity($this->driver->createQuery(Sql::select('*')->from($this->table->getName())->where("{$name}=:value"))->execute([':value' => $value])->loadAssoc());
    }

//    public function findAllByField(string ...$fields) {
//        $entities = [];
//        foreach ($this->driver->getQuery(Sql::select(implode(', ', $names))->from($this->table->getName()))->execute()->loadAssocs() as $item => $value) {
//            $entities[] = new $this->entityName();
//        }
//        return $entities;
//    }

    public function findAll(): array
    {
        return $this->createMultipleEntities($this->driver->createQuery(Sql::select('*')->from($this->table->getName()))->execute()->loadAssocs());
    }

//    public function existsByField(string $name, $value): bool
//    {
//        return
//    }

    public function createEntity(array $data) {
        return new $this->entityName($data);
    }

    public function createMultipleEntities(array $datas) {
        $entities = [];
        foreach ($datas as $key => $data) {
            $entities[] = new $this->entityName($data);
        }
        return $entities;
    }
}