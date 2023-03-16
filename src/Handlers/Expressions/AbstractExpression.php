<?php

declare(strict_types=1);

namespace Fi1a\MySql\Handlers\Expressions;

use Fi1a\MySql\ColumnTypes\ColumnTypeInterface;

/**
 * Условие
 */
abstract class AbstractExpression implements ExpressionInterface
{
    /**
     * @var string
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
     * @param mixed $value
     */
    public function __construct(string $column, $value, ColumnTypeInterface $type)
    {
        $this->column = $column;
        $this->value = $value;
        $this->type = $type;
    }
}
