<?php

declare(strict_types=1);

namespace Fi1a\Unit\MySql\TestCases;

use Fi1a\DB\Adapters\AdapterInterface;
use Fi1a\MySql\MySqlAdapter;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

/**
 * Тесты
 */
class TestCase extends PHPUnitTestCase
{
    protected function getAdapter(): AdapterInterface
    {
        return new MySqlAdapter(getenv('DB_DSN'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'));
    }
}
