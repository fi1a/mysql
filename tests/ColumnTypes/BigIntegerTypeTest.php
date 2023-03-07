<?php

declare(strict_types=1);

namespace Fi1a\Unit\MySql\ColumnTypes;

use Fi1a\DB\Facades\Schema;
use Fi1a\DB\Queries\Column;
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
                    ->default(100)
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
        $bigIntegerType = new BigIntegerType($this->getAdapter()->getConnection(), 'columnName');
        $this->assertEquals('100', $bigIntegerType->conversionTo(100));
        $this->assertEquals('100', $bigIntegerType->conversionTo('100'));
        $this->assertEquals('0', $bigIntegerType->conversionTo(0));
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
