<?php

declare(strict_types=1);

namespace Fi1a\MySql\Handlers\Expressions;

use Fi1a\MySql\ColumnTypes\ColumnTypeInterface;

/**
 * Реестр выражений
 */
interface RegistryInterface
{
    /**
     * Возвращает выражение по его операции
     *
     * @param mixed $value
     */
    public function get(string $operation, string $columnName, $value, ColumnTypeInterface $type): ExpressionInterface;

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
