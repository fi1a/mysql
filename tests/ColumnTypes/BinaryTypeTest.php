<?php

declare(strict_types=1);

namespace Fi1a\Unit\MySql\ColumnTypes;

use Fi1a\DB\Facades\Query;
use Fi1a\DB\Facades\Schema;
use Fi1a\DB\Queries\Column;
use Fi1a\MySql\ColumnTypes\BinaryType;
use Fi1a\Unit\MySql\TestCases\TestCase;

/**
 * Binary
 */
class BinaryTypeTest extends TestCase
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
                    ->binary()
            )
            ->column(
                Column::create()
                    ->name('columnNull')
                    ->binary()
                    ->nullable()
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
                    ->binary()
            )
            ->column(
                Column::create()
                    ->name('columnNull')
                    ->binary()
            );

        $query->rows([
            [
                'column' => 1,
                'columnNull' => null,
            ],
            [
                'column' => 2,
                'columnNull' => null,
            ],
            [
                'column' => 3,
                'columnNull' => null,
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
        $binaryType = new BinaryType($this->getAdapter()->getConnection(), 'columnName');
        $this->assertEquals('\'text\'', $binaryType->conversionTo('text'));
        $this->assertEquals('NULL', $binaryType->conversionTo(null));
    }

    /**
     * Приведение типа значения из БД
     */
    public function testConversionFrom(): void
    {
        $binaryType = new BinaryType($this->getAdapter()->getConnection(), 'columnName');
        $this->assertEquals('text', $binaryType->conversionFrom('text'));
    }
}
