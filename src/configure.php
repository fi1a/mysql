<?php

declare(strict_types=1);

use Fi1a\DI\Builder;
use Fi1a\MySql\ColumnTypes\BigIntegerType;
use Fi1a\MySql\ColumnTypes\BinaryType;
use Fi1a\MySql\ColumnTypes\BooleanType;
use Fi1a\MySql\ColumnTypes\CharType;
use Fi1a\MySql\ColumnTypes\DateTimeType;
use Fi1a\MySql\ColumnTypes\DateType;
use Fi1a\MySql\ColumnTypes\DecimalType;
use Fi1a\MySql\ColumnTypes\DoubleType;
use Fi1a\MySql\ColumnTypes\EnumType;
use Fi1a\MySql\ColumnTypes\FloatType;
use Fi1a\MySql\ColumnTypes\IntegerType;
use Fi1a\MySql\ColumnTypes\JsonType;
use Fi1a\MySql\ColumnTypes\LongTextType;
use Fi1a\MySql\ColumnTypes\MediumIntegerType;
use Fi1a\MySql\ColumnTypes\MediumTextType;
use Fi1a\MySql\ColumnTypes\Registry as CTRegistry;
use Fi1a\MySql\ColumnTypes\RegistryInterface as CTRegistryInterface;
use Fi1a\MySql\ColumnTypes\SmallIntegerType;
use Fi1a\MySql\ColumnTypes\StringType;
use Fi1a\MySql\ColumnTypes\TextType;
use Fi1a\MySql\ColumnTypes\TimeType;
use Fi1a\MySql\ColumnTypes\TimestampType;
use Fi1a\MySql\ColumnTypes\TinyIntegerType;
use Fi1a\MySql\Facades\ColumnTypeRegistry;
use Fi1a\MySql\Facades\ExpressionRegistry;
use Fi1a\MySql\Handlers\Expressions\EqExpression;
use Fi1a\MySql\Handlers\Expressions\Registry as ExpRegistry;
use Fi1a\MySql\Handlers\Expressions\RegistryInterface as ExpRegistryInterface;

di()->config()->addDefinition(
    Builder::build(CTRegistryInterface::class)
        ->defineFactory(function () {
            static $instance;

            // @codeCoverageIgnoreStart
            if ($instance === null) {
                $instance = new CTRegistry();
            }
            // @codeCoverageIgnoreEnd

            return $instance;
        })
        ->getDefinition()
);

di()->config()->addDefinition(
    Builder::build(ExpRegistryInterface::class)
        ->defineFactory(function () {
            static $instance;

            // @codeCoverageIgnoreStart
            if ($instance === null) {
                $instance = new ExpRegistry();
            }
            // @codeCoverageIgnoreEnd

            return $instance;
        })
        ->getDefinition()
);

ColumnTypeRegistry::add('integer', IntegerType::class);
ColumnTypeRegistry::add('bigInteger', BigIntegerType::class);
ColumnTypeRegistry::add('tinyInteger', TinyIntegerType::class);
ColumnTypeRegistry::add('smallInteger', SmallIntegerType::class);
ColumnTypeRegistry::add('mediumInteger', MediumIntegerType::class);
ColumnTypeRegistry::add('timestamp', TimestampType::class);
ColumnTypeRegistry::add('char', CharType::class);
ColumnTypeRegistry::add('string', StringType::class);
ColumnTypeRegistry::add('text', TextType::class);
ColumnTypeRegistry::add('mediumText', MediumTextType::class);
ColumnTypeRegistry::add('longText', LongTextType::class);
ColumnTypeRegistry::add('time', TimeType::class);
ColumnTypeRegistry::add('dateTime', DateTimeType::class);
ColumnTypeRegistry::add('date', DateType::class);
ColumnTypeRegistry::add('decimal', DecimalType::class);
ColumnTypeRegistry::add('double', DoubleType::class);
ColumnTypeRegistry::add('float', FloatType::class);
ColumnTypeRegistry::add('binary', BinaryType::class);
ColumnTypeRegistry::add('boolean', BooleanType::class);
ColumnTypeRegistry::add('json', JsonType::class);
ColumnTypeRegistry::add('enum', EnumType::class);

ExpressionRegistry::add('=', EqExpression::class);
