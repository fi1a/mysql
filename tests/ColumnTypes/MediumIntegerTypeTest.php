<?php

declare(strict_types=1);

namespace Fi1a\Unit\MySql\ColumnTypes;

use Fi1a\DB\Facades\Query;
use Fi1a\DB\Facades\Schema;
use Fi1a\DB\Queries\Column;
use Fi1a\MySql\ColumnTypes\MediumIntegerType;
use Fi1a\Unit\MySql\TestCases\TestCase;

/**
 * MediumInteger
 */
class MediumIntegerTypeTest extends TestCase
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
                    ->mediumInteger()
            )
            ->column(
                Column::create()
                    ->name('columnUnsigned')
                    ->mediumInteger(true)
            )
            ->column(
                Column::create()
                    ->name('columnNull')
                    ->mediumInteger()
                    ->nullable()
            )
            ->column(
                Column::create()
                    ->name('columnDefault')
                    ->mediumInteger()
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
                    ->mediumInteger()
            )
            ->column(
                Column::create()
                    ->name('columnUnsigned')
                    ->mediumInteger(true)
            )
            ->column(
                Column::create()
                    ->name('columnNull')
                    ->mediumInteger()
            )
            ->column(
                Column::create()
                    ->name('columnDefault')
                    ->mediumInteger()
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
        $mediumIntegerType = new MediumIntegerType($this->getAdapter()->getConnection(), 'columnName');
        $this->assertEquals('100', $mediumIntegerType->conversionTo(100));
        $this->assertEquals('100', $mediumIntegerType->conversionTo('100'));
        $this->assertEquals('0', $mediumIntegerType->conversionTo(0));
        $this->assertEquals('NULL', $mediumIntegerType->conversionTo(null));
    }

    /**
     * Приведение типа значения из БД
     */
    public function testConversionFrom(): void
    {
        $mediumIntegerType = new MediumIntegerType($this->getAdapter()->getConnection(), 'columnName');
        $this->assertEquals(100, $mediumIntegerType->conversionFrom('100'));
        $this->assertEquals(0, $mediumIntegerType->conversionFrom('0'));
    }
}
