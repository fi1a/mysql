<?php

declare(strict_types=1);

namespace Fi1a\MySql;

/**
 * Именование таблиц и колонок
 */
interface NamingInterface
{
    /**
     * Название таблицы
     */
    public function wrapTableName(string $tableName): string;

    /**
     * Название таблицы
     */
    public function wrapColumnName(string $columnName): string;
}
