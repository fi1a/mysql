<?php

declare(strict_types=1);

namespace Fi1a\Unit\MySql\ColumnTypes;

use Fi1a\DB\Facades\Schema;
use Fi1a\DB\Queries\Column;
use Fi1a\DB\Queries\Expressions\SqlExpression;
use Fi1a\MySql\ColumnTypes\TimestampType;
use Fi1a\Unit\MySql\TestCases\TestCase;

/**
 * Timestamp
 */
class TimestampTypeTest extends TestCase
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
                    ->timestamp()
            )
            ->column(
                Column::create()
                    ->name('columnNull')
                    ->timestamp()
                    ->nullable()
            )
            ->column(
                Column::create()
                    ->name('columnDefault')
                    ->timestamp()
                    ->default(new SqlExpression('CURRENT_TIMESTAMP'))
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
        $timestampType = new TimestampType($this->getAdapter()->getConnection(), 'columnName');
        $this->assertEquals('100', $timestampType->conversionTo(100));
        $this->assertEquals('100', $timestampType->conversionTo('100'));
        $this->assertEquals('0', $timestampType->conversionTo(0));
        $this->assertEquals(
            'CURRENT_TIMESTAMP',
            $timestampType->conversionTo(new SqlExpression('CURRENT_TIMESTAMP'))
        );
    }

    /**
     * Приведение типа значения из БД
     */
    public function testConversionFrom(): void
    {
        $timestampType = new TimestampType($this->getAdapter()->getConnection(), 'columnName');
        $this->assertEquals(100, $timestampType->conversionFrom('100'));
        $this->assertEquals(0, $timestampType->conversionFrom('0'));
    }
}
