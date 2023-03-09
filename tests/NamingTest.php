<?php

declare(strict_types=1);

namespace Fi1a\Unit\MySql;

use Fi1a\MySql\Naming;
use PHPUnit\Framework\TestCase;

/**
 * Именование таблиц и колонок
 */
class NamingTest extends TestCase
{
    /**
     * Название таблицы
     */
    public function testWrapTableNameWithoutPrefix(): void
    {
        $naming = new Naming(null);

        $this->assertEquals('', $naming->wrapTableName(''));
        $this->assertEquals('`tableName`', $naming->wrapTableName('`tableName`'));
        $this->assertEquals('`tableName`', $naming->wrapTableName('tableName'));
        $this->assertEquals('`databaseName`.`tableName`', $naming->wrapTableName('databaseName.tableName'));
        $this->assertEquals('`databaseName`.`tableName`', $naming->wrapTableName('`databaseName`.`tableName`'));
    }

    /**
     * Название таблицы
     */
    public function testWrapTableNameWithPrefix(): void
    {
        $naming = new Naming('prf_');

        $this->assertEquals('', $naming->wrapTableName(''));
        $this->assertEquals('`prf_tableName`', $naming->wrapTableName('`tableName`'));
        $this->assertEquals('`prf_tableName`', $naming->wrapTableName('tableName'));
        $this->assertEquals('`databaseName`.`prf_tableName`', $naming->wrapTableName('databaseName.tableName'));
        $this->assertEquals('`databaseName`.`prf_tableName`', $naming->wrapTableName('`databaseName`.`tableName`'));
    }

    /**
     * Название колонки
     */
    public function testWrapColumnNameWithoutPrefix(): void
    {
        $naming = new Naming(null);

        $this->assertEquals('', $naming->wrapColumnName(''));
        $this->assertEquals('`columnName`', $naming->wrapColumnName('`columnName`'));
        $this->assertEquals('`columnName`', $naming->wrapColumnName('columnName'));
        $this->assertEquals('`tableName`.`columnName`', $naming->wrapColumnName('tableName.columnName'));
        $this->assertEquals('`tableName`.`columnName`', $naming->wrapColumnName('`tableName`.`columnName`'));
        $this->assertEquals(
            '`databaseName`.`tableName`.`columnName`',
            $naming->wrapColumnName('databaseName.tableName.columnName')
        );
        $this->assertEquals(
            '`databaseName`.`tableName`.`columnName`',
            $naming->wrapColumnName('`databaseName`.`tableName`.`columnName`')
        );
    }

    /**
     * Название колонки
     */
    public function testWrapColumnNameWithPrefix(): void
    {
        $naming = new Naming('prf_');

        $this->assertEquals('', $naming->wrapColumnName(''));
        $this->assertEquals('`columnName`', $naming->wrapColumnName('`columnName`'));
        $this->assertEquals('`columnName`', $naming->wrapColumnName('columnName'));
        $this->assertEquals('`prf_tableName`.`columnName`', $naming->wrapColumnName('tableName.columnName'));
        $this->assertEquals('`prf_tableName`.`columnName`', $naming->wrapColumnName('`tableName`.`columnName`'));
        $this->assertEquals(
            '`databaseName`.`prf_tableName`.`columnName`',
            $naming->wrapColumnName('databaseName.tableName.columnName')
        );
        $this->assertEquals(
            '`databaseName`.`prf_tableName`.`columnName`',
            $naming->wrapColumnName('`databaseName`.`tableName`.`columnName`')
        );
    }
}
