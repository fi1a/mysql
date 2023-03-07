<?php

declare(strict_types=1);

namespace Fi1a\Unit\MySql\ColumnTypes;

use Fi1a\DB\Facades\Schema;
use Fi1a\DB\Queries\Column;
use Fi1a\MySql\ColumnTypes\LongTextType;
use Fi1a\Unit\MySql\TestCases\TestCase;

/**
 * LongText
 */
class LongTextTypeTest extends TestCase
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
                    ->longText()
            )
            ->column(
                Column::create()
                    ->name('columnNull')
                    ->longText()
                    ->nullable()
            )
            ->column(
                Column::create()
                    ->name('columnDefault')
                    ->longText()
                    ->default('text')
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
        $longTextType = new LongTextType($this->getAdapter()->getConnection(), 'columnName');
        $this->assertEquals('\'text\'', $longTextType->conversionTo('text'));
    }

    /**
     * Приведение типа значения из БД
     */
    public function testConversionFrom(): void
    {
        $longTextType = new LongTextType($this->getAdapter()->getConnection(), 'columnName');
        $this->assertEquals('text', $longTextType->conversionFrom('text'));
    }
}
