<?php

declare(strict_types=1);

namespace Fi1a\MySql\Handlers;

use Fi1a\DB\Adapters\HandlerInterface;
use Fi1a\MySql\NamingInterface;
use PDO;

/**
 * Обработчик MySql
 */
abstract class AbstractMySqlHandler implements HandlerInterface
{
    /**
     * @var PDO
     */
    protected $connection;

    /**
     * @var NamingInterface
     */
    protected $naming;

    public function __construct(PDO $connection, NamingInterface $naming)
    {
        $this->connection = $connection;
        $this->naming = $naming;
    }
}
