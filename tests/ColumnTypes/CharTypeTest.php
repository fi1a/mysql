<?php

declare(strict_types=1);

namespace Fi1a\Unit\MySql\ColumnTypes;

use Fi1a\DB\Exceptions\QueryErrorException;
use Fi1a\DB\Facades\Query;
use Fi1a\DB\Facades\Schema;
use Fi1a\DB\Queries\Column;
use Fi1a\MySql\ColumnTypes\CharType;
use Fi1a\Unit\MySql\TestCases\TestCase;

/**
 * Char
 */
class CharTypeTest extends TestCase
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
                    ->char()
            )
            ->column(
                Column::create()
                    ->name('columnNull')
                    ->char()
                    ->nullable()
            )
            ->column(
                Column::create()
                    ->name('columnDefault')
                    ->char()
                    ->nullable()
                    ->default('text')
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
                    ->char()
            )
            ->column(
                Column::create()
                    ->name('columnNull')
                    ->char()
            )
            ->column(
                Column::create()
                    ->name('columnDefault')
                    ->char()
            );

        $query->rows([
            [
                'column' => 'foo',
                'columnNull' => null,
                'columnDefault' => null,
            ],
            [
                'column' => 'bar',
                'columnNull' => null,
                'columnDefault' => null,
            ],
            [
                'column' => 'baz',
                'columnNull' => null,
                'columnDefault' => null,
            ],
        ]);

        $this->assertTrue($adapter->exec($query));
    }

    /**
     * Создание таблицы с типом
     */
    public function testCreateTableWithTypeLengthException(): void
    {
        $this->expectException(QueryErrorException::class);
        $adapter = $this->getAdapter();

        $query = Schema::create()
            ->name('tableName')
            ->column(
                Column::create()
                    ->name('column')
                    ->char(300)
            );

        $adapter->exec($query);
    }

    /**
     * Создание таблицы с типом
     */
    public function testCreateTableWithTypeLengthZeroException(): void
    {
        $this->expectException(QueryErrorException::class);
        $adapter = $this->getAdapter();

        $query = Schema::create()
            ->name('tableName')
            ->column(
                Column::create()
                    ->name('column')
                    ->char(0)
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
        $charType = new CharType($this->getAdapter()->getConnection(), 'columnName');
        $this->assertEquals('\'text\'', $charType->conversionTo('text'));
    }

    /**
     * Приведение типа значения к записи в БД
     */
    public function testConversionToException(): void
    {
        $this->expectException(QueryErrorException::class);
        $charType = new CharType($this->getAdapter()->getConnection(), 'columnName', ['length' => 1]);
        $this->assertEquals('\'text\'', $charType->conversionTo('text'));
    }

    /**
     * Приведение типа значения из БД
     */
    public function testConversionFrom(): void
    {
        $charType = new CharType($this->getAdapter()->getConnection(), 'columnName');
        $this->assertEquals('text', $charType->conversionFrom('text'));
    }
}
