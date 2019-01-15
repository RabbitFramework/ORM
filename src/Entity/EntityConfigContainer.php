<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 12/01/2019
 * Time: 19:06
 */

namespace Rabbit\ORM\Repository;


use Rabbit\File\Drivers\Ini;

/**
 * Class EntityConfigContainer
 * @package Rabbit\ORM\Repository
 */
class EntityConfigContainer
{

    /**
     * @var
     */
    private $path;

    /**
     * @var
     */
    private $parser;

    /**
     * @var array
     */
    private $entities = [];

    /**
     * @var array
     */
    private $entitiesRepositories = [];

    /**
     * @var
     */
    public static $_instance;

    /**
     * @return EntityConfigContainer
     */
    public static function getInstance() {
        if(!isset(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * EntityConfigContainer constructor.
     */
    protected function __construct() {}


    /**
     * @param string $path
     * @return $this
     * @throws \Rabbit\File\Drivers\DriverException
     */
    public function setPath(string $path) {
        $this->path = $path;
        $this->parse();
        return $this;
    }

    /**
     * @return $this
     * @throws \Rabbit\File\Drivers\DriverException
     */
    private function parse() {
        $this->parser = new Ini($this->path);
        $this->entities = explode(', ', $this->parser->getKey('ENTITIES'));
        $this->entitiesRepositories = explode(', ', $this->parser->getKey('ENTITIES_REPOSITORIES'));
        return $this;
    }

    /**
     * @return array
     */
    public function getEntities(): array
    {
        return $this->entities;
    }

    /**
     * @param array $entities
     * @return $this
     */
    public function setEntities(array $entities)
    {
        $this->entities = $entities;
        return $this;
    }

    /**
     * @return array
     */
    public function getEntitiesRepositories(): array
    {
        return $this->entitiesRepositories;
    }

    /**
     * @param array $entitiesRepositories
     * @return $this
     */
    public function setEntitiesRepositories(array $entitiesRepositories)
    {
        $this->entitiesRepositories = $entitiesRepositories;
        return $this;
    }

}