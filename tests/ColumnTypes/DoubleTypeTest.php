<?php

declare(strict_types=1);

namespace Fi1a\Unit\MySql\ColumnTypes;

use Fi1a\DB\Facades\Query;
use Fi1a\DB\Facades\Schema;
use Fi1a\DB\Queries\Column;
use Fi1a\MySql\ColumnTypes\DoubleType;
use Fi1a\Unit\MySql\TestCases\TestCase;

/**
 * Double
 */
class DoubleTypeTest extends TestCase
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
                    ->double()
            )
            ->column(
                Column::create()
                    ->name('columnUnsigned')
                    ->double(true, 8, 2)
            )
            ->column(
                Column::create()
                    ->name('columnNull')
                    ->double()
                    ->nullable()
            )
            ->column(
                Column::create()
                    ->name('columnDefault')
                    ->double()
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
                    ->double()
            )
            ->column(
                Column::create()
                    ->name('columnUnsigned')
            )
            ->column(
                Column::create()
                    ->name('columnNull')
                    ->double()
            )
            ->column(
                Column::create()
                    ->name('columnDefault')
                    ->double()
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
        $doubleType = new DoubleType($this->getAdapter()->getConnection(), 'columnName');
        $this->assertEquals('100.02', $doubleType->conversionTo(100.02));
        $this->assertEquals('100', $doubleType->conversionTo('100'));
        $this->assertEquals('0', $doubleType->conversionTo(0));
        $this->assertEquals('NULL', $doubleType->conversionTo(null));
    }

    /**
     * Приведение типа значения из БД
     */
    public function testConversionFrom(): void
    {
        $doubleType = new DoubleType($this->getAdapter()->getConnection(), 'columnName');
        $this->assertEquals(100.02, $doubleType->conversionFrom('100.02'));
        $this->assertEquals(0, $doubleType->conversionFrom('0'));
    }
}
