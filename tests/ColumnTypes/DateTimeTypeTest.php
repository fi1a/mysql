<?php

declare(strict_types=1);

namespace Fi1a\Unit\MySql\ColumnTypes;

use Fi1a\DB\Facades\Query;
use Fi1a\DB\Facades\Schema;
use Fi1a\DB\Queries\Column;
use Fi1a\MySql\ColumnTypes\DateTimeType;
use Fi1a\Unit\MySql\TestCases\TestCase;

/**
 * DateTime
 */
class DateTimeTypeTest extends TestCase
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
                    ->dateTime()
            )
            ->column(
                Column::create()
                    ->name('columnNull')
                    ->dateTime()
                    ->nullable()
            )
            ->column(
                Column::create()
                    ->name('columnDefault')
                    ->dateTime()
                    ->nullable()
                    ->default('2023-03-07 01:00:00')
            );

        $this->assertTrue($adapter->exec($query));
    }

    /**
     * Вставка значений
     *
     * @depends testCreateTableWithType
     */
    public function testInsertWithType(): void
    {
        $adapter = $this->getAdapter();

        $query = Query::insert()
            ->name('tableName')
            ->column(
                Column::create()
                    ->name('column')
                    ->dateTime()
            )
            ->column(
                Column::create()
                    ->name('columnNull')
                    ->dateTime()
            )
            ->column(
                Column::create()
                    ->name('columnDefault')
                    ->dateTime()
            );

        $query->rows([
            [
                'column' => '2023-03-07 01:00:00',
                'columnNull' => null,
                'columnDefault' => null,
            ],
            [
                'column' => '2023-03-08 01:00:00',
                'columnNull' => null,
                'columnDefault' => null,
            ],
            [
                'column' => '2023-03-09 01:00:00',
                'columnNull' => null,
                'columnDefault' => null,
            ],
        ]);

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
        $dateTimeType = new DateTimeType($this->getAdapter()->getConnection(), 'columnName');
        $this->assertEquals('\'2023-03-07 01:00:00\'', $dateTimeType->conversionTo('2023-03-07 01:00:00'));
        $this->assertEquals('NULL', $dateTimeType->conversionTo(null));
    }

    /**
     * Приведение типа значения из БД
     */
    public function testConversionFrom(): void
    {
        $dateTimeType = new DateTimeType($this->getAdapter()->getConnection(), 'columnName');
        $this->assertEquals('2023-03-07 01:00:00', $dateTimeType->conversionFrom('2023-03-07 01:00:00'));
    }
}
