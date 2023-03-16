<?php

declare(strict_types=1);

namespace Fi1a\MySql\Handlers\Expressions;

use Fi1a\MySql\ColumnTypes\ColumnTypeInterface;

/**
 * Выражение в sql
 */
interface ExpressionInterface
{
    /**
     * @param mixed $value
     */
    public function __construct(string $column, $value, ColumnTypeInterface $type);

    /**
     * Возвращает sql выражения
     */
    public function getSql(): string;
}
