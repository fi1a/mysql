<?php

declare(strict_types=1);

namespace Fi1a\Unit\MySql\Facades;

use Fi1a\MySql\Facades\ExpressionRegistry;
use PHPUnit\Framework\TestCase;

/**
 * Реестр условий
 */
class ExpressionRegistryTest extends TestCase
{
    /**
     * Фасад
     */
    public function testFacade(): void
    {
        $this->assertFalse(ExpressionRegistry::has('unknown'));
    }
}
