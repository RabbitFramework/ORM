<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 21/12/2018
 * Time: 15:44
 */

namespace Rabbit\ORM\Builders\Entities;


/**
 * Interface UpdateEntityInterface
 * @package Rabbit\ORM\Builders\Entities
 */
interface UpdateEntityInterface extends BaseEntityInterface
{

    /**
     * @param string $name
     * @return self
     */
    public function update(string $name);

    /**
     * @param string ...$names
     * @return self
     */
    public function column(string ...$names);

    /**
     * @param string ...$values
     * @return self
     */
    public function values(string ...$values);

    /**
     * @return string
     */
    public function getUpdate() : string;

    /**
     * @return string
     */
    public function getColumn() : string;

}