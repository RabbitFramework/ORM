<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 25/12/2018
 * Time: 19:51
 */

namespace Rabbit\ORM\Mapper\EntityRows;


use Rabbit\ORM\Builders\QueryInterface;
use Rabbit\ORM\Drivers\DriverInterface;
use Rabbit\ORM\Mapper\EntityTable;

/**
 * Trait RowTraitEntity
 * @package Rabbit\ORM\Mapper\EntityRows
 */
trait RowEntityTrait
{
    /**
     * @var DriverInterface
     */
    private $driver;

    /**
     * @var QueryInterface
     */
    private $builder;

    /**
     * @var EntityTable
     */
    private $table;

    /**
     * @var
     */
    protected $primaryKeyRef;

    /**
     * @param string $name
     * @param $value
     * @return $this
     */
    public function setValue(string $name, $value) {
        if($this->hasValue($name)) {
            $this->$name = $value;
        }
        return $this;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function getValue(string $name) {
        if($this->hasValue($name))
            return $this->$name;
        return false;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function resetValue(string $name) {
        if($this->hasValue($name)) $this->$name = '';
        return $this;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasValue(string $name) {
        return isset($this->$name);
    }
}