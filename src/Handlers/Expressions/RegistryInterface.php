<?php

declare(strict_types=1);

namespace Fi1a\MySql\Handlers\Expressions;

use Fi1a\MySql\ColumnTypes\ColumnTypeInterface;
use Fi1a\MySql\NamingInterface;

/**
 * Реестр выражений
 */
interface RegistryInterface
{
    /**
     * Возвращает выражение по его операции
     *
     * @param mixed $columnName
     * @param mixed $value
     */
    public function get(
        string $operation,
        $columnName,
        $value,
        ColumnTypeInterface $type,
        NamingInterface $naming
    ): ExpressionInterface;

    /**
     * Добавляет выражение
     */
    public function add(string $operation, string $className): void;

    /**
     * Проверяет наличие выражения
     */
    public function has(string $operation): bool;

    /**
     * Удаляет выражение
     */
    public function remove(string $operation): bool;
}
