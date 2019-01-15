<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 10/01/2019
 * Time: 18:07
 */

namespace Rabbit\ORM\Repository;


use Rabbit\ORM\Builders\Sql;
use Rabbit\ORM\Drivers\DriverInterface;

/**
 * Class TableData
 * @package Rabbit\ORM\Repository
 */
class TableData
{

    /**
     * @var DriverInterface
     */
    private $driver;

    /**
     * @var string
     */
    public $name;

    /**
     * @var null
     */
    public $primaryName = null;

    /**
     * @var null
     */
    public $columnsNames = null;

    /**
     * @var null
     */
    public $columnsTypes = null;

    /**
     * @var int
     */
    public $rowCount = null;

    /**
     * @var null
     */
    public $columnCont = null;

    /**
     * TableData constructor.
     * @param DriverInterface $driver
     * @param string $name
     */
    public function __construct(DriverInterface $driver, string $name)
    {
        $this->driver = $driver;
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    /**
     *
     */
    public function getPrimaryName()
    {
        if(empty($this->primaryName)) {
            $this->driver->createQuery(Sql::select('column_name')->from('information_schema.columns')->where('table_name=:name')->andWhere('extra=:extra'))->execute([':name' => $this->name, ':extra' => 'auto_increment']);
            $this->primaryName = $this->driver->getQuery()->loadColumn();
        }
        return $this->primaryName;
    }

    /**
     *
     */
    public function getRowCount()
    {
        if(empty($this->rowCount))
        {
            $this->driver->createQuery(Sql::select('COUNT(*)')->from($this->name))->execute();
            if($this->driver->getQuery()->isExecuted) $this->rowCount = $this->driver->getQuery()->loadColumn();
        }
        return $this->rowCount;
    }

    /**
     *
     */
    public function getColumnsNames()
    {
        if(empty($this->columnsNames))
        {
            $this->driver->add(Sql::select('column_name')->from('information_schema.columns')->where('table_name=:table_name'))->execute([':table_name' => $this->name]);
            if($this->driver->getQuery()->isExecuted) $this->columnsNames = $this->driver->getQuery()->loadColumn();
        }
        return $this->columnsNames;
    }

    /**
     *
     */
    public function getColumnsTypes()
    {
        if(empty($this->columnsTypes)) {
            $this->driver->add(Sql::select('data_type')->from('information_schema.columns')->where('table_name=:table_name'))->execute([':table_name' => $this->name]);
            $this->columnsTypes = $this->driver->getQuery()->loadColumns();
        }
        return $this->columnsTypes;
    }

    /**
     *
     */
    public function getColumnCount()
    {
        if(empty($this->columnCont))
        {
            $this->driver->createQuery(Sql::select('COUNT(*)')->from('information_schema.columns')->where('table_name=:table_name'))->execute([':table_name' => $this->name]);
            if($this->driver->getQuery()->isExecuted) $this->columnCont = $this->driver->getQuery()->loadColumn();
        }
        return $this->columnCont;
    }

}