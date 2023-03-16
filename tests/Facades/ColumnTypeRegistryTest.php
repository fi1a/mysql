<?php

declare(strict_types=1);

namespace Fi1a\Unit\MySql\Facades;

use Fi1a\MySql\Facades\ColumnTypeRegistry;
use PHPUnit\Framework\TestCase;

/**
 * Реестр типа колонок
 */
class ColumnTypeRegistryTest extends TestCase
{
    /**
     * Фасад
     */
    public function testFacade(): void
    {
        $this->assertFalse(ColumnTypeRegistry::has('unknown'));
    }
}
