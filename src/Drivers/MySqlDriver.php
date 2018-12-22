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

class MySqlDriver extends BaseDriver
{

    public function __construct(string $host, string $database, string $username, string $password, string $charset = 'utf8')
    {
        if(extension_loaded('pdo_mysql')) {
            parent::__construct(['host' => $host, 'database' => $database, 'username' => $username, 'password' => $password, 'charset' => $charset]);
            $this->getConnection();
        } else {
            throw new DriverException('[Rabbit => ORM->MySqlDriver::__construct()] Unable to construct the class because the extension `pdo_mysql` is not loaded in the php.ini');
        }
    }

    public function getConnection() {
        if(!$this->hasConnection()) {
            try {
                $this->_connection = new \PDO('mysql:host='.$this->_connectionParameters['host'].';dbname='.$this->_connectionParameters['database'].';charset='.$this->_connectionParameters['charset'], $this->_connectionParameters['username'], $this->_connectionParameters['password']);
            } catch (\PDOException $e) {
                throw new DriverException('[Rabbit => Database->MySqlDriver::getConnection()] Unable to create an instance of PDO class: '.$e);
            }
        }
        return $this->_connection;
    }

    public function execute(int $id = null) {
        if($this->hasQuery($id ?? $this->_currentQuery)) {
            $query = $this->queries[$id ?? $this->_currentQuery];
            if(!$query->isPrepared()) {
                $this->prepare($id ?? $this->_currentQuery);
            }
            $query->setExecuted($query->getPreparedQuery()->execute());
            return $this;
        }
    }

    public function prepare(int $id = null) {
        if($this->hasQuery($id)) {
            $this->queries[$id]->setPreparedQuery($this->_connection->prepare($this->queries[$id]->getQuery()));
            foreach ($this->queries[$id]->getParameters() as $name => $value) {
                $this->queries[$id]->getPreparedQuery()->bindParam($name, is_array($value) ? $value['value'] : $value, is_array($value) ? $value['type'] : null);
            }
            foreach ($this->queries[$id]->getValues() as $name => $value) {
                $this->queries[$id]->getPreparedQuery()->bindValue($name, $value);
            }
            return $this;
        }
    }

    public function loadObject(int $id = null) {
        if($this->queries[$id ?? $this->_currentQuery]->isExecuted()) {
            return $this->queries[$id ?? $this->_currentQuery]->getPreparedQuery()->fetch(\PDO::FETCH_OBJ);
        } else {
            return new \stdClass();
        }
    }

    public function loadObjects(int $id = null) {
        if($this->queries[$id ?? $this->_currentQuery]->isExecuted()) {
            return $this->queries[$id ?? $this->_currentQuery]->getPreparedQuery()->fetchAll(\PDO::FETCH_OBJ);
        } else {
            return [];
        }
    }

    public function loadAssoc(int $id = null) {
        if($this->queries[$id ?? $this->_currentQuery]->isExecuted()) {
            return $this->queries[$id ?? $this->_currentQuery]->getPreparedQuery()->fetch(\PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }

    public function loadAssocs(int $id = null) {
        if($this->queries[$id ?? $this->_currentQuery]->isExecuted()) {
            return $this->queries[$id ?? $this->_currentQuery]->getPreparedQuery()->fetchAll(\PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }

    public function loadColumn(int $id = null) {
        if($this->queries[$id ?? $this->_currentQuery]->isExecuted()) {
            return $this->queries[$id ?? $this->_currentQuery]->getPreparedQuery()->fetch(\PDO::FETCH_COLUMN);
        } else {
            return '';
        }
    }

    public function loadColumns(int $id = null) {
        if($this->queries[$id ?? $this->_currentQuery]->isExecuted()) {
            return $this->queries[$id ?? $this->_currentQuery]->getPreparedQuery()->fetchAll(\PDO::FETCH_COLUMN);
        } else {
            return [];
        }
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
}