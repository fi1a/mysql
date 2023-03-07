<?php

declare(strict_types=1);

namespace Fi1a\MySql\ColumnTypes;

use PDO;

/**
 * Обработчик типа
 */
interface ColumnTypeInterface
{
    /**
     * @param array<string, mixed>|null $params
     */
    public function __construct(PDO $connection, string $columnName, ?array $params = null);

    /**
     * Возвращает SQL для создания типа колонки
     */
    public function getSql(): string;

    /**
     * Приведение типа к БД
     *
     * @param mixed $value
     */
    public function conversionTo($value): string;

    /**
     * Приведение типа из БД
     *
     * @return mixed
     */
    public function conversionFrom(string $value);
}
