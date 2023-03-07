<?php

declare(strict_types=1);

namespace Fi1a\MySql\ColumnTypes;

use PDO;

/**
 * Реестр типа колонок
 */
interface RegistryInterface
{
    /**
     * Возвращает обработчик типа колонки по его названию
     *
     * @param array<string, mixed>|null $params
     */
    public function get(string $type, PDO $connection, string $columnName, ?array $params = null): ColumnTypeInterface;

    /**
     * Добавляет обработчик типа колонки
     */
    public function add(string $type, string $className): void;

    /**
     * Проверяет наличие обработчика типа колонки
     */
    public function has(string $type): bool;

    /**
     * Удаляет обработчик типа колонки
     */
    public function remove(string $type): bool;
}
