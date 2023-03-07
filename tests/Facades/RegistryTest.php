<?php

declare(strict_types=1);

namespace Fi1a\Unit\MySql\Facades;

use Fi1a\MySql\Facades\Registry;
use PHPUnit\Framework\TestCase;

/**
 * Реестр типа колонок
 */
class RegistryTest extends TestCase
{
    /**
     * Фасад
     */
    public function testFacade(): void
    {
        $this->assertFalse(Registry::has('unknown'));
    }
}
