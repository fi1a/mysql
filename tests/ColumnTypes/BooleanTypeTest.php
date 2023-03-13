<?php

declare(strict_types=1);

namespace Fi1a\Unit\MySql\ColumnTypes;

use Fi1a\DB\Facades\Query;
use Fi1a\DB\Facades\Schema;
use Fi1a\DB\Queries\Column;
use Fi1a\MySql\ColumnTypes\BooleanType;
use Fi1a\Unit\MySql\TestCases\TestCase;

/**
 * Boolean
 */
class BooleanTypeTest extends TestCase
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
                    ->boolean()
            )
            ->column(
                Column::create()
                    ->name('columnNull')
                    ->boolean()
                    ->nullable()
            )
            ->column(
                Column::create()
                    ->name('columnDefault')
                    ->boolean()
                    ->nullable()
                    ->default(true)
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
                    ->boolean()
            )
            ->column(
                Column::create()
                    ->name('columnNull')
                    ->boolean()
            )
            ->column(
                Column::create()
                    ->name('columnDefault')
                    ->boolean()
            );

        $query->rows([
            [
                'column' => true,
                'columnNull' => null,
                'columnDefault' => null,
            ],
            [
                'column' => true,
                'columnNull' => null,
                'columnDefault' => null,
            ],
            [
                'column' => false,
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
        $booleanType = new BooleanType($this->getAdapter()->getConnection(), 'columnName');
        $this->assertEquals('1', $booleanType->conversionTo(true));
        $this->assertEquals('0', $booleanType->conversionTo(false));
        $this->assertEquals('1', $booleanType->conversionTo(1));
        $this->assertEquals('0', $booleanType->conversionTo(0));
        $this->assertEquals('1', $booleanType->conversionTo('1'));
        $this->assertEquals('0', $booleanType->conversionTo('0'));
        $this->assertEquals('0', $booleanType->conversionTo('value'));
    }

    /**
     * Приведение значения типа из БД
     */
    public function testConversionFrom(): void
    {
        $booleanType = new BooleanType($this->getAdapter()->getConnection(), 'columnName');
        $this->assertEquals(true, $booleanType->conversionFrom('1'));
        $this->assertEquals(false, $booleanType->conversionFrom('0'));
    }
}
