<?php

declare(strict_types=1);

namespace Fi1a\Unit\MySql\ColumnTypes;

use Fi1a\DB\Facades\Query;
use Fi1a\DB\Facades\Schema;
use Fi1a\DB\Queries\Column;
use Fi1a\MySql\ColumnTypes\DecimalType;
use Fi1a\Unit\MySql\TestCases\TestCase;

/**
 * Decimal
 */
class DecimalTypeTest extends TestCase
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
                    ->decimal()
            )
            ->column(
                Column::create()
                    ->name('columnUnsigned')
                    ->decimal(true)
            )
            ->column(
                Column::create()
                    ->name('columnNull')
                    ->decimal()
                    ->nullable()
            )
            ->column(
                Column::create()
                    ->name('columnDefault')
                    ->decimal()
                    ->nullable()
                    ->default(100.02)
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
                    ->decimal()
            )
            ->column(
                Column::create()
                    ->name('columnUnsigned')
                    ->decimal()
            )
            ->column(
                Column::create()
                    ->name('columnNull')
                    ->decimal()
            )
            ->column(
                Column::create()
                    ->name('columnDefault')
                    ->decimal()
            );

        $query->rows([
            [
                'column' => 1.02,
                'columnUnsigned' => 1.02,
                'columnNull' => null,
                'columnDefault' => null,
            ],
            [
                'column' => 2.02,
                'columnUnsigned' => 2.02,
                'columnNull' => null,
                'columnDefault' => null,
            ],
            [
                'column' => 3.02,
                'columnUnsigned' => 3.02,
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
        $decimalType = new DecimalType($this->getAdapter()->getConnection(), 'columnName');
        $this->assertEquals('100', $decimalType->conversionTo(100));
        $this->assertEquals('100', $decimalType->conversionTo('100'));
        $this->assertEquals('0', $decimalType->conversionTo(0));
        $this->assertEquals('NULL', $decimalType->conversionTo(null));
    }

    /**
     * Приведение типа значения из БД
     */
    public function testConversionFrom(): void
    {
        $decimalType = new DecimalType($this->getAdapter()->getConnection(), 'columnName');
        $this->assertEquals(100, $decimalType->conversionFrom('100'));
        $this->assertEquals(0, $decimalType->conversionFrom('0'));
    }
}
