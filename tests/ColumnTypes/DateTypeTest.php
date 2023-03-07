<?php

declare(strict_types=1);

namespace Fi1a\Unit\MySql\ColumnTypes;

use Fi1a\DB\Facades\Schema;
use Fi1a\DB\Queries\Column;
use Fi1a\MySql\ColumnTypes\DateType;
use Fi1a\Unit\MySql\TestCases\TestCase;

/**
 * Text
 */
class DateTypeTest extends TestCase
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
                    ->date()
            )
            ->column(
                Column::create()
                    ->name('columnNull')
                    ->date()
                    ->nullable()
            )
            ->column(
                Column::create()
                    ->name('columnDefault')
                    ->date()
                    ->default('2023-03-07')
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
        $dateType = new DateType($this->getAdapter()->getConnection(), 'columnName');
        $this->assertEquals('\'2023-03-07\'', $dateType->conversionTo('2023-03-07'));
    }

    /**
     * Приведение типа значения из БД
     */
    public function testConversionFrom(): void
    {
        $dateType = new DateType($this->getAdapter()->getConnection(), 'columnName');
        $this->assertEquals('2023-03-07', $dateType->conversionFrom('2023-03-07'));
    }
}
