<?php

declare(strict_types=1);

namespace Fi1a\Unit\MySql\ColumnTypes;

use Fi1a\DB\Facades\Schema;
use Fi1a\DB\Queries\Column;
use Fi1a\MySql\ColumnTypes\JsonType;
use Fi1a\Unit\MySql\TestCases\TestCase;

/**
 * Json
 */
class JsonTypeTest extends TestCase
{
    /**
     * Создание таблицы с типом
     */
    public function testCreateTableWithType(): void
    {
        $adapter = $this->getAdapter();

        $query = Schema::create()
            ->name('tableName')
            ->column(
                Column::create()
                    ->name('column')
                    ->json()
            )
            ->column(
                Column::create()
                    ->name('columnNull')
                    ->json()
                    ->nullable()
            )
            ->column(
                Column::create()
                    ->name('columnDefault')
                    ->json()
                    ->default([1, 2, 3])
            );

        $this->assertTrue($adapter->exec($query));
    }

    /**
     * Удаление таблицы с типом
     *
     * @depends testCreateTableWithType
     */
    public function testDropTableWithType(): void
    {
        $adapter = $this->getAdapter();

        $query = Schema::drop()
            ->name('tableName');

        $this->assertTrue($adapter->exec($query));
    }

    /**
     * Приведение типа значения к записи в БД
     */
    public function testConversionTo(): void
    {
        $jsonType = new JsonType($this->getAdapter()->getConnection(), 'columnName');
        $this->assertEquals('\'[1,2,3]\'', $jsonType->conversionTo([1, 2, 3]));
    }

    /**
     * Приведение типа значения из БД
     */
    public function testConversionFrom(): void
    {
        $jsonType = new JsonType($this->getAdapter()->getConnection(), 'columnName');
        $this->assertEquals([1, 2, 3], $jsonType->conversionFrom('[1, 2, 3]'));
    }
}
