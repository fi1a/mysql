<?php

declare(strict_types=1);

namespace Fi1a\Unit\MySql\ColumnTypes;

use Fi1a\DB\Facades\Query;
use Fi1a\DB\Facades\Schema;
use Fi1a\DB\Queries\Column;
use Fi1a\MySql\ColumnTypes\IntegerType;
use Fi1a\Unit\MySql\TestCases\TestCase;

/**
 * Integer
 */
class IntegerTypeTest extends TestCase
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
                    ->integer()
            )
            ->column(
                Column::create()
                    ->name('columnUnsigned')
                    ->integer(true)
            )
            ->column(
                Column::create()
                    ->name('columnNull')
                    ->integer()
                    ->nullable()
            )
            ->column(
                Column::create()
                    ->name('columnDefault')
                    ->integer()
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
                    ->integer()
            )
            ->column(
                Column::create()
                    ->name('columnUnsigned')
                    ->integer(true)
            )
            ->column(
                Column::create()
                    ->name('columnNull')
                    ->integer()
            )
            ->column(
                Column::create()
                    ->name('columnDefault')
                    ->integer()
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
     * Приведение значения типа к записи в БД
     */
    public function testConversionTo(): void
    {
        $integerType = new IntegerType($this->getAdapter()->getConnection(), 'columnName');
        $this->assertEquals('100', $integerType->conversionTo(100));
        $this->assertEquals('100', $integerType->conversionTo('100'));
        $this->assertEquals('0', $integerType->conversionTo(0));
        $this->assertEquals('NULL', $integerType->conversionTo(null));
    }

    /**
     * Приведение значения типа из БД
     */
    public function testConversionFrom(): void
    {
        $integerType = new IntegerType($this->getAdapter()->getConnection(), 'columnName');
        $this->assertEquals(100, $integerType->conversionFrom('100'));
        $this->assertEquals(0, $integerType->conversionFrom('0'));
    }
}
