<?php

declare(strict_types=1);

namespace Fi1a\Unit\MySql\ColumnTypes;

use Fi1a\DB\Facades\Schema;
use Fi1a\DB\Queries\Column;
use Fi1a\MySql\ColumnTypes\TimeType;
use Fi1a\Unit\MySql\TestCases\TestCase;

/**
 * Text
 */
class TimeTypeTest extends TestCase
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
                    ->time()
            )
            ->column(
                Column::create()
                    ->name('columnNull')
                    ->time()
                    ->nullable()
            )
            ->column(
                Column::create()
                    ->name('columnDefault')
                    ->time()
                    ->default('01:00:00')
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
        $timeType = new TimeType($this->getAdapter()->getConnection(), 'columnName');
        $this->assertEquals('\'01:00:00\'', $timeType->conversionTo('01:00:00'));
    }

    /**
     * Приведение типа значения из БД
     */
    public function testConversionFrom(): void
    {
        $timeType = new TimeType($this->getAdapter()->getConnection(), 'columnName');
        $this->assertEquals('01:00:00', $timeType->conversionFrom('01:00:00'));
    }
}
