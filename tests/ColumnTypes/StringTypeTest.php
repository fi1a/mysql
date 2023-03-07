<?php

declare(strict_types=1);

namespace Fi1a\Unit\MySql\ColumnTypes;

use Fi1a\DB\Exceptions\QueryErrorException;
use Fi1a\DB\Facades\Schema;
use Fi1a\DB\Queries\Column;
use Fi1a\MySql\ColumnTypes\StringType;
use Fi1a\Unit\MySql\TestCases\TestCase;

/**
 * String
 */
class StringTypeTest extends TestCase
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
                    ->string()
            )
            ->column(
                Column::create()
                    ->name('columnNull')
                    ->string()
                    ->nullable()
            )
            ->column(
                Column::create()
                    ->name('columnDefault')
                    ->string()
                    ->default('text')
            );

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
                    ->string(300)
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
                    ->string(0)
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
        $stringType = new StringType($this->getAdapter()->getConnection(), 'columnName');
        $this->assertEquals('\'text\'', $stringType->conversionTo('text'));
    }

    /**
     * Приведение типа значения к записи в БД
     */
    public function testConversionToException(): void
    {
        $this->expectException(QueryErrorException::class);
        $stringType = new StringType($this->getAdapter()->getConnection(), 'columnName', ['length' => 1]);
        $this->assertEquals('\'text\'', $stringType->conversionTo('text'));
    }

    /**
     * Приведение типа значения из БД
     */
    public function testConversionFrom(): void
    {
        $stringType = new StringType($this->getAdapter()->getConnection(), 'columnName');
        $this->assertEquals('text', $stringType->conversionFrom('text'));
    }
}
