<?php

declare(strict_types=1);

namespace Fi1a\MySql\ColumnTypes;

use Fi1a\DB\Exceptions\QueryErrorException;

/**
 * Enum
 */
class EnumType extends AbstractType
{
    /**
     * @inheritDoc
     */
    public function getSql(): string
    {
        if (!isset($this->params['enums']) || !is_array($this->params['enums']) || !count($this->params['enums'])) {
            throw new QueryErrorException(sprintf('Не переданы значения списка %s', $this->columnName));
        }
        $enums = '';

        /** @var mixed $enum */
        foreach ($this->params['enums'] as $enum) {
            $enums .= ($enums ? ', ' : '') . $this->connection->quote((string) $enum);
        }

        return "ENUM($enums)";
    }

    /**
     * @inheritDoc
     */
    public function conversionTo($value): string
    {
        if ($value === null) {
            return 'NULL';
        }

        return $this->connection->quote((string) $value);
    }

    /**
     * @inheritDoc
     */
    public function conversionFrom(string $value)
    {
        return $value;
    }
}
