<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 30/11/2018
 * Time: 18:17
 */

namespace Rabbit\ORM\Drivers;

use Rabbit\ORM\Builders\QueryInterface;
use Rabbit\DependencyContainer\DependencyContainer;
use Rabbit\ORM\Builders\Sql;
use Rabbit\ORM\Drivers\Attributes\MySqlAttributeCollection;

class MySqlDriver extends BaseDriver
{

    public function __construct(string $host, string $database, string $username, string $password, string $charset = 'utf8', array $attributes = [], array $attributesValues = [])
    {
        if(extension_loaded('pdo_mysql')) {
            parent::__construct(['host' => $host, 'database' => $database, 'username' => $username, 'password' => $password, 'charset' => $charset, 'attributes' => $attributes, 'attributesValues' => $attributesValues]);
            $this->getConnection();
        } else {
            throw new DriverException('[Rabbit => Database->MySqlDriver::__construct()] Unable to construct the class because the extension `pdo_mysql` is not loaded in the php.ini');
        }
    }

    public function getConnection() {
        if(!$this->hasConnection()) {
            try {
                $this->_connection = new \PDO('mysql:host=' . $this->_connectionParameters['host'] . ';dbname=' . $this->_connectionParameters['database'] . ';charset=' . $this->_connectionParameters['charset'], $this->_connectionParameters['username'], $this->_connectionParameters['password']);
                $attributeCollection = new MySqlAttributeCollection();
                foreach ($this->_connectionParameters['attributes'] as $key => $attribute) {
                    if($attributeCollection->has($attribute) && isset($this->_connectionParameters['attributesValues'][$key])) {
                        $this->_connection->setAttribute($attributeCollection->get($attribute), $this->_connectionParameters['attributesValues'][$key]);
                    }
                }
            } catch (\PDOException $e) {
                throw new DriverException('[Rabbit => Database->MySqlDriver::getConnection()] Unable to create an instance of PDO class: '.$e);
            }
        }
        return $this->_connection;
    }

    public function execute(int $id = null) {
        if($this->hasQuery($id ?? $this->_currentQuery)) {
            $this->queries[$id ?? $this->_currentQuery]->execute();
            return $this;
        }
    }

    public function prepare(int $id = null) {
        if($this->hasQuery($id ?? $this->_currentQuery)) {
            $this->queries[$id ?? $this->_currentQuery]->prepare();
            return $this;
        }
    }

    public function loadObject(int $id = null) {
        if($this->queries[$id ?? $this->_currentQuery]->isExecuted()) {
            return $this->queries[$id ?? $this->_currentQuery]->getPreparedQuery()->fetch(\PDO::FETCH_OBJ);
        }
        return new \stdClass();
    }

    public function loadObjects(int $id = null) {
        if($this->queries[$id ?? $this->_currentQuery]->isExecuted()) {
            return $this->queries[$id ?? $this->_currentQuery]->getPreparedQuery()->fetchAll(\PDO::FETCH_OBJ);
        }
        return [];
    }

    public function loadAssoc(int $id = null) {
        if($this->queries[$id ?? $this->_currentQuery]->isExecuted()) {
            return $this->queries[$id ?? $this->_currentQuery]->getPreparedQuery()->fetch(\PDO::FETCH_ASSOC);
        }
        return [];
    }

    public function loadAssocs(int $id = null) {
        if($this->queries[$id ?? $this->_currentQuery]->isExecuted()) {
            return $this->queries[$id ?? $this->_currentQuery]->getPreparedQuery()->fetchAll(\PDO::FETCH_ASSOC);
        }
        return [];
    }

    public function loadColumn(int $id = null) {
        if($this->queries[$id ?? $this->_currentQuery]->isExecuted()) {
            return $this->queries[$id ?? $this->_currentQuery]->getPreparedQuery()->fetch(\PDO::FETCH_COLUMN);
        }
        return '';
    }

    public function loadColumns(int $id = null) {
        if($this->queries[$id ?? $this->_currentQuery]->isExecuted()) {
            return $this->queries[$id ?? $this->_currentQuery]->getPreparedQuery()->fetchAll(\PDO::FETCH_COLUMN);
        }
        return [];
    }

    public function closeCursor() {
        if($this->queries[$id ?? $this->_currentQuery]->isExecuted()) {
            $this->queries[$id ?? $this->_currentQuery]->getPreparedQuery()->closeCursor();
        }
        return $this;
    }

    public function rowCount(int $id = null) {
        if($this->queries[$id ?? $this->_currentQuery]->isExecuted()) {
            return $this->queries[$id ?? $this->_currentQuery]->getPreparedQuery()->rowCount();
        } else {
            return 0;
        }
    }

    public function getBuilder() : QueryInterface {
        return DependencyContainer::getInstance()->get(Sql::class)->getInstance(['driver' => $this]);
    }

    public function getDriverErrorCode()
    {
        return $this->_connection->errorCode();
    }

    public function getDriverErrorInfo()
    {
        return $this->_connection->errorInfo();
    }

    public function getQueryErrorCode(int $id = null)
    {
        if(!$this->queries[$id ?? $this->_currentQuery]->isExecuted()) {
            return $this->queries[$id ?? $this->_currentQuery]->getPreparedQuery()->errorCode();
        }
    }

    public function getQueryErrorInfo(int $id = null)
    {
        if(!$this->queries[$id ?? $this->_currentQuery]->isExecuted()) {
            return $this->queries[$id ?? $this->_currentQuery]->getPreparedQuery()->errorInfo();
        }
    }
}