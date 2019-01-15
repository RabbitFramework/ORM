<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 05/01/2019
 * Time: 10:35
 */

namespace Rabbit\ORM\Repository;


class Entity
{

    public function __construct(array $data = [])
    {
        $this->assignData($data);
    }

    public function getInstance() {
        return new self();
    }

    public function assignData(array $data) {
        foreach ($data as $item => $value) {
            $item = 'set'.$item;
            if(method_exists($this, $item)) {
                $this->$item(intval($value) ? intval($value) : $value);
            } else {
                // Todo: Add exception throwing
            }
        }
    }

}