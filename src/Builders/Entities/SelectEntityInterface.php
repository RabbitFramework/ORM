<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 21/12/2018
 * Time: 15:44
 */

namespace Rabbit\ORM\Builders\Entities;


/**
 * Interface SelectEntityInterface
 * @package Rabbit\ORM\Builders\Entities
 */
interface SelectEntityInterface extends BaseEntityInterface
{
    /**
     * @param string ...$names
     * @return $this
     */
    public function andSelect(string ...$names);

    /**
     * @param string $from
     * @return $this
     */
    public function from(string $from);

    /**
     * @return string
     */
    public function getSelect() : string;

    /**
     * @return string
     */
    public function getFrom() : string;
}