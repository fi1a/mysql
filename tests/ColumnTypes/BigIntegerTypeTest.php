<?php

declare(strict_types=1);

namespace Fi1a\Unit\MySql\ColumnTypes;

use Fi1a\DB\Facades\Query;
use Fi1a\DB\Facades\Schema;
use Fi1a\DB\Queries\AndWhere;
use Fi1a\DB\Queries\Column;
use Fi1a\DB\Queries\ColumnType;
use Fi1a\MySql\ColumnTypes\BigIntegerType;
use Fi1a\Unit\MySql\TestCases\TestCase;

/**
 * BigInteger
 */
class BigIntegerTypeTest extends TestCase
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
                    ->bigInteger()
            )
            ->column(
                Column::create()
                    ->name('columnUnsigned')
                    ->bigInteger(true)
            )
            ->column(
                Column::create()
                    ->name('columnNull')
                    ->bigInteger()
                    ->nullable()
            )
            ->column(
                Column::create()
                    ->name('columnDefault')
                    ->bigInteger()
                    ->nullable()
                    ->default(100)
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
                    ->bigInteger()
            )
            ->column(
                Column::create()
                    ->name('columnUnsigned')
                    ->bigInteger()
            )
            ->column(
                Column::create()
                    ->name('columnNull')
                    ->bigInteger()
            )
            ->column(
                Column::create()
                    ->name('columnDefault')
                    ->bigInteger()
            );

        $query->rows([
            [
                'column' => 1,
                'columnUnsigned' => 1,
                'columnNull' => null,
                'columnDefault' => null,
            ],
            [
                'column' => 2,
                'columnUnsigned' => 2,
                'columnNull' => null,
                'columnDefault' => null,
            ],
            [
                'column' => 3,
                'columnUnsigned' => 3,
                'columnNull' => null,
                'columnDefault' => null,
            ],
        ]);

        $this->assertTrue($adapter->exec($query));
    }

    /**
     * Запрс выборки
     */
    public function testSelectWhereLine(): void
    {
        $adapter = $this->getAdapter();

        $query = Query::select()
            ->from('tableName')
            ->column(ColumnType::create()
                ->name('columnUnsigned')
                ->bigInteger(true))
            ->column(ColumnType::create()
                ->name('column')
                ->bigInteger())
            ->where('column', '=', 1)
            ->andWhere('columnUnsigned', '=', 1);

        $items = $adapter->query($query);

        $this->assertCount(1, $items);
        $this->assertEquals([
            [
                'column' => 1,
                'columnUnsigned' => 1,
            ],
        ], $items);
    }

    /**
     * Запрс выборки
     */
    public function testSelectWhereNested(): void
    {
        $adapter = $this->getAdapter();

        $query = Query::select()
            ->from('tableName', 'tableAlias')
            ->column(ColumnType::create()
                ->name('columnUnsigned')
                ->bigInteger(true))
            ->where('column', '=', 1)
            ->orWhere(
                AndWhere::create('columnUnsigned', '=', 1)
                ->orWhere('columnUnsigned', '=', 2)
            );

        $items = $adapter->query($query);

        $this->assertCount(2, $items);
        $this->assertEquals([
            [
                'columnUnsigned' => 1,
            ],
            [
                'columnUnsigned' => 2,
            ],
        ], $items);
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
        $bigIntegerType = new BigIntegerType($this->getAdapter()->getConnection(), 'columnName');
        $this->assertEquals('100', $bigIntegerType->conversionTo(100));
        $this->assertEquals('100', $bigIntegerType->conversionTo('100'));
        $this->assertEquals('0', $bigIntegerType->conversionTo(0));
        $this->assertEquals('NULL', $bigIntegerType->conversionTo(null));
    }

    /**
     * Приведение типа значения из БД
     */
    public function testConversionFrom(): void
    {
        $bigIntegerType = new BigIntegerType($this->getAdapter()->getConnection(), 'columnName');
        $this->assertEquals(100, $bigIntegerType->conversionFrom('100'));
        $this->assertEquals(0, $bigIntegerType->conversionFrom('0'));
    }
}
