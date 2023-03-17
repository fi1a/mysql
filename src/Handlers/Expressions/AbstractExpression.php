<?php

declare(strict_types=1);

namespace Fi1a\MySql\Handlers\Expressions;

use Fi1a\MySql\ColumnTypes\ColumnTypeInterface;
use Fi1a\MySql\NamingInterface;

/**
 * Условие
 */
abstract class AbstractExpression implements ExpressionInterface
{
    /**
     * @var mixed
     */
    protected $column;

    /**
     * @var mixed
     */
    protected $value;

    /**
     * @var ColumnTypeInterface
     */
    protected $type;

    /**
     * @var NamingInterface
     */
    protected $naming;

    /**
     * @param mixed $column
     * @param mixed $value
     */
    public function __construct($column, $value, ColumnTypeInterface $type, NamingInterface $naming)
    {
        $this->column = $column;
        $this->value = $value;
        $this->type = $type;
        $this->naming = $naming;
    }
}
