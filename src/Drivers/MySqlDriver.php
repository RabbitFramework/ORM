<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 30/11/2018
 * Time: 18:17
 */

namespace Rabbit\ORM\Drivers;

use Rabbit\ORM\Builders\BuilderInterface;
use Rabbit\ORM\Builders\QueryInterface;
use Rabbit\DependencyContainer\DependencyContainer;
use Rabbit\ORM\Builders\Sql;
use Rabbit\ORM\Drivers\Attributes\MySqlAttributeCollection;

final class MySqlDriver extends BaseDriver
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

    public function getBuilder() : BuilderInterface {
        return DependencyContainer::getInstance()->get(Sql::class)->getInstance(['driver' => $this]);
    }

    public function getErrorCode()
    {
        return $this->_connection->errorCode();
    }

    public function getErrorInfo()
    {
        return $this->_connection->errorInfo();
    }
}