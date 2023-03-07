<?php

declare(strict_types=1);

namespace Fi1a\Unit\MySql\ColumnTypes;

use Fi1a\DB\Exceptions\UnknownTypeException;
use Fi1a\MySql\ColumnTypes\IntegerType;
use Fi1a\MySql\ColumnTypes\Registry;
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
        $this->assertFalse($registry->has('integer'));
        $registry->add('integer', IntegerType::class);
        $this->assertTrue($registry->has('integer'));
        $registry->add('integer', IntegerType::class);
        $this->assertTrue($registry->has('integer'));
        $this->assertInstanceOf(
            IntegerType::class,
            $registry->get('integer', $this->getAdapter()->getConnection(), 'columnName')
        );
        $this->assertTrue($registry->remove('integer'));
        $this->assertFalse($registry->remove('integer'));
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
        $this->expectException(UnknownTypeException::class);
        $registry = new Registry();
        $registry->get('unknown', $this->getAdapter()->getConnection(), 'columnName');
    }
}
