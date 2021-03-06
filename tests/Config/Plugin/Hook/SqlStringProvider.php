<?php
namespace Psalm\Test\Config\Plugin\Hook;

use Psalm\Plugin\Hook\StringInterpreterInterface;
use Psalm\Type\Atomic\TLiteralString;
use function stripos;

class SqlStringProvider implements StringInterpreterInterface
{
    public static function getTypeFromValue(string $value) : ?TLiteralString
    {
        if (stripos($value, 'select ') !== false) {
            try {
                $parser = new \PhpMyAdmin\SqlParser\Parser($value);

                if (!$parser->errors) {
                    return new StringProvider\TSqlSelectString($value);
                }
            } catch (\Throwable $e) {
                // fall through
            }
        }

        return null;
    }
}
