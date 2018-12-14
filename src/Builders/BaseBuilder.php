<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 08/12/2018
 * Time: 18:10
 */

namespace Rabbit\ORM\Builders;

/**
 * Class BaseBuilder
 * @package Rabbit\ORM\Builders
 */
abstract class BaseBuilder
{
    /**
     * CONST Types
     */
    const SELECT = 0;
    const INSERT = 1;
    const CREATE = 2;
    const DROP = 3;
    const ALTER = 4;
    const UPDATE = 5;

    /**
     * CONST Others
     */
        /**
         * SELECT, INSERT => Operations
         */
    const FROM = 100; // ==> SELECT Type
    const COLUMNS = 101;
    const VALUES = 102;
    const AS = 103; // ==> SELECT & FROM Type/Other
        /**
         * WHERE => Operations
         */
    const WHERE = 200;
    const AND = 201;
    const OR = 202;
        /**
         * TABLES => Operations
        */
    const TABLE = 300;
    const SET = 301;
        /**
         * COLUMNS => Operations
         */
    const COLUMN = 400;
    const DATATYPE = 401;

    /**
     * FIELDS Generic
     */
    protected $type = self::SELECT;
    protected $lastExecuted;
    public $query;

    /**
     * FIELDS Query
     */
        /**
         * Query => Generic Field
         */
    public $where = [];
        /**
         * SELECT => Fields
         */
    public $select = [];
    public $from = '';
        /**
         * INSERT => Fields
         */
    public $insert = '';
    public $columns = [];
    public $values = [];
        /**
         * ALTER|DROP|CREATE|UPDATE => Fields
         */
    public $table = '';
    public $column = '';
    public $dataType = 'VARCHAR(50)';
    /**
     *
     */
    protected function initFields() {
        $this->query = '';
        $this->select = [];
        $this->from = '';
        $this->where = [];
        $this->insert = '';
        $this->values = [];
        $this->create = '';
        $this->drop = '';
    }

    /**
     * @param int $max
     * @return string
     */
    public function generateInt(int $max = 0) {
        return "INT($max)";
    }

    /**
     * @param int $max
     * @return string
     */
    public function generateTinyInt(int $max = 0) {
        return "TINYINT($max)";
    }

    /**
     * @param int $max
     * @return string
     */
    public function generateVarchar(int $max = 0) {
        return "VARCHAR($max)";
    }
}