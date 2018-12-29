<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 25/12/2018
 * Time: 18:09
 */

namespace Rabbit\ORM\Mapper\EntityColumn;


use Rabbit\ORM\Builders\QueryInterface;
use Rabbit\ORM\Drivers\DriverInterface;
use Rabbit\ORM\Mapper\EntityTable;
use Rabbit\ORM\Mapper\MapperInterface;

/**
 * Trait ColumnEntityTrait
 * @package Rabbit\ORM\Mapper\EntityColumn
 */
trait ColumnEntityTrait
{
    /**
     * @var DriverInterface
     */
    protected $driver;

    /**
     * @var QueryInterface
     */
    protected $builder;

    /**
     * @var EntityTable
     */
    protected $table;

    /**
     * @var
     */
    protected $columnName;

    /**
     * @var
     */
    public $values = [];

    /**
     * @param int $position
     * @param string $value
     */
    public function setValue(int $position, string $value) {

    }

    /**
     * @param $value
     */
    public function addValue($value) {

    }

    /**
     * @param int $position
     */
    public function delete(int $position) {

    }

    /**
     *
     */
    public function updateLocalValues() {

    }

    /**
     *
     */
    public function getOnlineValues() {

    }

    /**
     *
     */
    public function getValuePosition() {

    }
}