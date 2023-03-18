<?php

declare(strict_types=1);

namespace Fi1a\Unit\MySql\ColumnTypes;

use Fi1a\DB\Facades\Query;
use Fi1a\DB\Facades\Schema;
use Fi1a\DB\Queries\Column;
use Fi1a\MySql\ColumnTypes\SmallIntegerType;
use Fi1a\Unit\MySql\TestCases\TestCase;

/**
 * SmallInteger
 */
class SmallIntegerTypeTest extends TestCase
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
                    ->smallInteger()
            )
            ->column(
                Column::create()
                    ->name('columnUnsigned')
                    ->smallInteger(true)
            )
            ->column(
                Column::create()
                    ->name('columnNull')
                    ->smallInteger()
                    ->nullable()
            )
            ->column(
                Column::create()
                    ->name('columnDefault')
                    ->smallInteger()
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
                    ->smallInteger()
            )
            ->column(
                Column::create()
                    ->name('columnUnsigned')
                    ->smallInteger(true)
            )
            ->column(
                Column::create()
                    ->name('columnNull')
                    ->smallInteger()
            )
            ->column(
                Column::create()
                    ->name('columnDefault')
                    ->smallInteger()
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
     * Приведение типа значения к записи в БД
     */
    public function testConversionTo(): void
    {
        $smallIntegerType = new SmallIntegerType($this->getAdapter()->getConnection(), 'columnName');
        $this->assertEquals('100', $smallIntegerType->conversionTo(100));
        $this->assertEquals('100', $smallIntegerType->conversionTo('100'));
        $this->assertEquals('0', $smallIntegerType->conversionTo(0));
        $this->assertEquals('NULL', $smallIntegerType->conversionTo(null));
    }

    /**
     * Приведение типа значения из БД
     */
    public function testConversionFrom(): void
    {
        $smallIntegerType = new SmallIntegerType($this->getAdapter()->getConnection(), 'columnName');
        $this->assertEquals(100, $smallIntegerType->conversionFrom('100'));
        $this->assertEquals(0, $smallIntegerType->conversionFrom('0'));
    }
}
