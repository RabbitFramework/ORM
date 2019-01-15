<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 01/12/2018
 * Time: 19:17
 */

namespace Rabbit\ORM;

use Rabbit\File\Drivers\Ini;
use Rabbit\ORM\Drivers\BaseDriver;

/**
 * Class DatabaseConfigContainer
 * @package Rabbit\ORM
 */
class DatabaseConfigContainer
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
     * @var string
     */
    private $dbType = "";

    /**
     * @var string
     */
    private $dbHost = "";

    /**
     * @var string
     */
    private $dbName = "";

    /**
     * @var string
     */
    private $dbUser = "";

    /**
     * @var string
     */
    private $dbPassword = "";

    /**
     * @var string
     */
    private $dbCharset = 'utf8';

    /**
     * @var array
     */
    private $dbAttributes = [];

    /**
     * @var array
     */
    private $dbAttributesValues = [];

    /**
     * @var
     */
    public static $_instance;

    /**
     * @return DatabaseConfigContainer
     */
    public static function getInstance() {
        if(!isset(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * DatabaseConfigContainer constructor.
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
        $this->dbType = $this->parser->getKey('TYPE');
        $this->dbHost = $this->parser->getKey('HOST');
        $this->dbName = $this->parser->getKey('DATABASE');
        $this->dbUser = $this->parser->getKey('USERNAME');
        $this->dbPassword = $this->parser->getKey('PASSWORD');
        $this->dbCharset = $this->parser->getKey('CHARSET');
        $this->dbAttributes = explode(', ', $this->parser->getKey('ATTRIBUTES'));
        $this->dbAttributesValues = explode(', ', $this->parser->getKey('ATTRIBUTES_VALUES'));
        return $this;
    }

    /**
     * @return string
     */
    public function getDbType(): string
    {
        return $this->dbType;
    }

    /**
     * @param string $dbType
     * @return $this
     */
    public function setDbType(string $dbType)
    {
        $this->dbType = $dbType;
        return $this;
    }

    /**
     * @return string
     */
    public function getDbHost(): string
    {
        return $this->dbHost;
    }

    /**
     * @param string $dbHost
     * @return $this
     */
    public function setDbHost(string $dbHost)
    {
        $this->dbHost = $dbHost;
        return $this;
    }

    /**
     * @return string
     */
    public function getDbName(): string
    {
        return $this->dbName;
    }

    /**
     * @param string $dbName
     * @return $this
     */
    public function setDbName(string $dbName)
    {
        $this->dbName = $dbName;
        return $this;
    }

    /**
     * @return string
     */
    public function getDbUser(): string
    {
        return $this->dbUser;
    }

    /**
     * @param string $dbUser
     * @return $this
     */
    public function setDbUser(string $dbUser)
    {
        $this->dbUser = $dbUser;
        return $this;
    }

    /**
     * @return string
     */
    public function getDbPassword(): string
    {
        return $this->dbPassword;
    }

    /**
     * @param string $dbPassword
     * @return $this
     */
    public function setDbPassword(string $dbPassword)
    {
        $this->dbPassword = $dbPassword;
        return $this;
    }

    /**
     * @return string
     */
    public function getDbCharset(): string
    {
        return $this->dbCharset;
    }

    /**
     * @param string $dbCharset
     * @return $this
     */
    public function setDbCharset(string $dbCharset)
    {
        $this->dbCharset = $dbCharset;
        return $this;
    }

    /**
     * @return array
     */
    public function getDbAttributes(): array
    {
        return $this->dbAttributes;
    }

    /**
     * @param array $dbAttributes
     * @return $this
     */
    public function setDbAttributes(array $dbAttributes)
    {
        $this->dbAttributes = $dbAttributes;
        return $this;
    }

    /**
     * @return array
     */
    public function getDbAttributesValues(): array
    {
        return $this->dbAttributesValues;
    }

    /**
     * @param array $dbAttributesValues
     * @return $this
     */
    public function setDbAttributesValues(array $dbAttributesValues)
    {
        $this->dbAttributesValues = $dbAttributesValues;
        return $this;
    }

}