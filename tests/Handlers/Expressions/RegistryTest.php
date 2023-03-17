<?php

declare(strict_types=1);

namespace Fi1a\Unit\MySql\Handlers\Expressions;

use Fi1a\DB\Exceptions\UnknownExpressionException;
use Fi1a\MySql\Facades\ColumnTypeRegistry;
use Fi1a\MySql\Handlers\Expressions\EqExpression;
use Fi1a\MySql\Handlers\Expressions\Registry;
use Fi1a\MySql\Naming;
use Fi1a\Unit\MySql\TestCases\TestCase;
use InvalidArgumentException;

/**
 * Реестр типа колонок
 */
class RegistryTest extends TestCase
{
    /**
     * Реестр типа колонок
     */
    public function testRegistry(): void
    {
        $registry = new Registry();
        $this->assertFalse($registry->has('='));
        $registry->add('=', EqExpression::class);
        $this->assertTrue($registry->has('='));
        $registry->add('=', EqExpression::class);
        $this->assertTrue($registry->has('='));
        $this->assertInstanceOf(
            EqExpression::class,
            $registry->get(
                '=',
                'columnName',
                1,
                ColumnTypeRegistry::get(
                    'bigInteger',
                    $this->getAdapter()->getConnection(),
                    'columnName'
                ),
                new Naming(null)
            )
        );
        $this->assertTrue($registry->remove('='));
        $this->assertFalse($registry->remove('='));
    }

    /**
     * Исключение если не реаилизуется интерфейс
     */
    public function testAddException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $registry = new Registry();
        $registry->add('type', static::class);
    }

    /**
     * Исключение при неизвестном типе
     */
    public function testUnknownTypeException(): void
    {
        $this->expectException(UnknownExpressionException::class);
        $registry = new Registry();
        $registry->get(
            'unknown',
            'columnName',
            1,
            ColumnTypeRegistry::get('bigInteger', $this->getAdapter()->getConnection(), 'columnName'),
            new Naming(null)
        );
    }
}
