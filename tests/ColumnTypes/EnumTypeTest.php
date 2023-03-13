<?php

declare(strict_types=1);

namespace Fi1a\Unit\MySql\ColumnTypes;

use Fi1a\DB\Exceptions\QueryErrorException;
use Fi1a\DB\Facades\Query;
use Fi1a\DB\Facades\Schema;
use Fi1a\DB\Queries\Column;
use Fi1a\MySql\ColumnTypes\EnumType;
use Fi1a\Unit\MySql\TestCases\TestCase;

/**
 * Enum
 */
class EnumTypeTest extends TestCase
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
                    ->enum(['red', 'green', 'blue'])
            )
            ->column(
                Column::create()
                    ->name('columnNull')
                    ->enum(['red', 'green', 'blue'])
                    ->nullable()
            )
            ->column(
                Column::create()
                    ->name('columnDefault')
                    ->enum(['red', 'green', 'blue'])
                    ->nullable()
                    ->default('red')
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
                    ->enum(['red', 'green', 'blue'])
            )
            ->column(
                Column::create()
                    ->name('columnNull')
                    ->enum(['red', 'green', 'blue'])
            )
            ->column(
                Column::create()
                    ->name('columnDefault')
                    ->enum(['red', 'green', 'blue'])
            );

        $query->rows([
            [
                'column' => 'red',
                'columnNull' => null,
                'columnDefault' => null,
            ],
            [
                'column' => 'green',
                'columnNull' => null,
                'columnDefault' => null,
            ],
            [
                'column' => 'blue',
                'columnNull' => null,
                'columnDefault' => null,
            ],
        ]);

        $this->assertTrue($adapter->exec($query));
    }

    /**
     * Создание таблицы с типом
     */
    public function testEmptyEnumException(): void
    {
        $this->expectException(QueryErrorException::class);
        $adapter = $this->getAdapter();

        $query = Schema::create()
            ->name('tableName')
            ->column(
                Column::create()
                    ->name('column')
                    ->enum([])
            );

        $adapter->exec($query);
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
        $enumType = new EnumType($this->getAdapter()->getConnection(), 'columnName');
        $this->assertEquals('\'red\'', $enumType->conversionTo('red'));
    }

    /**
     * Приведение типа значения из БД
     */
    public function testConversionFrom(): void
    {
        $enumType = new EnumType($this->getAdapter()->getConnection(), 'columnName');
        $this->assertEquals('red', $enumType->conversionFrom('red'));
    }
}
