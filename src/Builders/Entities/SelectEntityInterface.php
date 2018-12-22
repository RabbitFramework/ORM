<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 21/12/2018
 * Time: 15:44
 */

namespace Rabbit\ORM\Builders\Entities;


interface SelectEntityInterface extends BaseEntityInterface
{
    public function andSelect(string ...$names);

    public function from(string $from);

    public function getSelect() : string;

    public function getFrom() : string;
}