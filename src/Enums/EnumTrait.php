<?php

namespace Laraditz\Twilio\Enums;

trait EnumTrait
{
    public static function getCase(string $value): self|null
    {
        $cases = self::cases();
        $matchingCase = array_search($value, array_column($cases, "name"));

        if ($matchingCase !== false) {
            return data_get($cases, $matchingCase);
        }

        return null;
    }

    public static function getValue(string $value): int|null
    {
        $case = self::getCase($value);

        return $case !== null ? $case->value : $case;
    }
}
