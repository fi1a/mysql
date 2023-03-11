<?php

declare(strict_types=1);

namespace Fi1a\MySql;

use Fi1a\DB\Adapters\AbstractSqlAdapter;
use Fi1a\DB\Adapters\HandlerInterface;
use Fi1a\DB\Exceptions\QueryErrorException;
use Fi1a\MySql\Handlers\AddIndexHandler;
use Fi1a\MySql\Handlers\CreateTableHandler;
use Fi1a\MySql\Handlers\DropIndexHandler;
use Fi1a\MySql\Handlers\DropTableHandler;
use PDO;
use PDOException;

/**
 * Адаптер MySql
 */
class MySqlAdapter extends AbstractSqlAdapter
{
    /**
     * @var PDO
     */
    protected $connection;

    /**
     * @var NamingInterface
     */
    protected $naming;

    /**
     * @param array<string, mixed>|null $options
     */
    public function __construct(
        string $dsn,
        ?string $username = null,
        ?string $password = null,
        ?array $options = null,
        ?string $tablePrefix = null
    ) {
        $this->connection = new PDO($dsn, $username, $password, $options);
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->naming = new Naming($tablePrefix);
    }

    /**
     * @inheritDoc
     */
    public function execSql(string $sql)
    {
        try {
            $result = $this->connection->exec($sql);
        } catch (PDOException $exception) {
            throw new QueryErrorException($exception->getMessage(), (int) $exception->getCode(), $exception);
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function querySql(string $sql): array
    {
        $items = false;
        try {
            $statement = $this->connection->query($sql);
            if ($statement) {
                /** @var array<array-key, array<string, string>>|false $items */
                $items = $statement->fetchAll(PDO::FETCH_ASSOC);
            }
        } catch (PDOException $exception) {
            throw new QueryErrorException($exception->getMessage(), (int) $exception->getCode(), $exception);
        }

        return $items === false ? [] : $items;
    }

    /**
     * Возвращает соединение
     */
    public function getConnection(): PDO
    {
        return $this->connection;
    }

    /**
     * @inheritDoc
     */
    protected function getHandler(string $type): HandlerInterface
    {
        switch ($type) {
            case 'createTable':
                return new CreateTableHandler($this->connection, $this->naming);
            case 'dropTable':
                return new DropTableHandler($this->connection, $this->naming);
            case 'addIndex':
                return new AddIndexHandler($this->connection, $this->naming);
            case 'dropIndex':
                return new DropIndexHandler($this->connection, $this->naming);
        }

        throw new QueryErrorException(sprintf('Неизвестный запрос %s', $type));
    }
}
