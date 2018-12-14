<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 30/11/2018
 * Time: 18:17
 */

namespace Rabbit\ORM\Builders;

/**
 * Interface QueryInterface
 * @package Rabbit\ORM\Builders
 */
interface QueryInterface
{

    /**
     * @param string ...$select
     * @return $this
     */
    public function select(string ...$select);

    /**
     * @param $reference
     * @param $alias
     * @return mixed
     */
    public function as($reference, $alias);

    /**
     * @param string $from
     * @return $this
     */
    public function from(string $from);

    /**
     * @param string ...$where
     * @return $this
     */
    public function where(string ...$where);

    /**
     * @param int ...$index
     * @return mixed
     */
    public function and(int ...$index);

    /**
     * @param int ...$index
     * @return mixed
     */
    public function or(int ...$index);

    /**
     * @param string $insert
     * @return $this
     */
    public function insert(string $insert);

    /**
     * @param string ...$column
     * @return mixed
     */
    public function columns(string ...$column);

    /**
     * @param string ...$value
     * @return $this
     */
    public function values(string ...$value);

    /**
     * @return mixed
     */
    public function alter();

    /**
     * @return mixed
     */
    public function create();

    /**
     * @return mixed
     */
    public function drop();

    /**
     * @return mixed
     */
    public function table(string $table);

    /**
     * @param string $column
     * @return mixed
     */
    public function column(string $column);

    /**
     * @return mixed
     */
    public function dataType(string $type);

    /**
     * @return mixed
     */
    public function getSql();

    /**
     * @return string
     */
    public function __toString();

}