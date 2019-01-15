<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 12/01/2019
 * Time: 18:04
 */

namespace Rabbit\ORM\Repository;


use Rabbit\DependencyContainer\DependencyContainer;
use Rabbit\ORM\Database;

/**
 * Class EntityManager
 * @package Rabbit\ORM\Repository
 */
class EntityManager implements EntityManagerInterface
{

    /**
     * @var EntityConfigContainer
     */
    private $config;
    /**
     * @var \Rabbit\ORM\Drivers\DriverInterface
     */
    private $driver;

    /**
     * @var
     */
    public static $_instance;

    /**
     * @return EntityManager
     */
    public static function getInstance() {
        if(!isset(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * EntityManager constructor.
     */
    protected function __construct()
    {
        $this->config = EntityConfigContainer::getInstance();
        $this->driver = Database::getInstance()->getDriver();
    }

    /**
     * @param $entity
     */
    public function persist(&$entity)
    {
        // TODO: Implement persist() method.
    }

    /**
     * @param int $id
     * @return EntityInterface
     */
    public function retrieve(int $id): EntityInterface
    {
        // TODO: Implement retrieve() method.
    }

    /**
     * @param int $id
     */
    public function delete(int $id)
    {
        // TODO: Implement delete() method.
    }

    /**
     * @param int $id
     */
    public function has(int $id)
    {
        // TODO: Implement has() method.
    }

    /**
     * @param string $query
     */
    public function createQuery(string $query) {
        $this->driver->createQuery($query);
    }

    /**
     * @param string $entityName
     * @return RepositoryInterface
     */
    public function getRepository(string $entityName) : object {
        if(array_search($entityName, $this->config->getEntities()) !== false) {
            return isset($this->config->getEntitiesRepositories()[array_search($entityName, $this->config->getEntities())]) ? \Rabbit\DependencyInjector\Container::getInstance()->get($this->config->getEntitiesRepositories()[array_search($entityName, $this->config->getEntities())])->setRule(['constructParameters' => ['manager' => $this, 'driver' => $this->driver]])->getInstance() : null;
        }
    }

    public function getRepositoryName(string $entityName) : string {
        if(array_search($entityName, $this->config->getEntities()) !== false) {
            return isset($this->config->getEntitiesRepositories()[array_search($entityName, $this->config->getEntities())]) ? $this->config->getEntitiesRepositories()[array_search($entityName, $this->config->getEntities())] : null;
        }
    }

    public function getEntity(string $repositoryName) : object {
        if(array_search($repositoryName, $this->config->getEntitiesRepositories()) !== false) {
            return isset($this->config->getEntities()[array_search($repositoryName, $this->config->getEntitiesRepositories())]) ? \Rabbit\DependencyInjector\Container::getInstance()->get($this->config->getEntities()[array_search($repositoryName, $this->config->getEntitiesRepositories())])->getInstance() : null;
        }
    }

    public function getEntityName(string $repositoryName) : string {
        if(array_search($repositoryName, $this->config->getEntitiesRepositories()) !== false) {
            return isset($this->config->getEntities()[array_search($repositoryName, $this->config->getEntitiesRepositories())]) ? $this->config->getEntities()[array_search($repositoryName, $this->config->getEntitiesRepositories())] : null;
        }
    }
}