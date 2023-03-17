<?php

declare(strict_types=1);

namespace Fi1a\MySql\Handlers\Expressions;

use Fi1a\MySql\ColumnTypes\ColumnTypeInterface;
use Fi1a\MySql\NamingInterface;

/**
 * Выражение в sql
 */
interface ExpressionInterface
{
    /**
     * @param mixed $column
     * @param mixed $value
     */
    public function __construct($column, $value, ColumnTypeInterface $type, NamingInterface $naming);

    /**
     * Возвращает sql выражения
     */
    public function getSql(): string;
}
