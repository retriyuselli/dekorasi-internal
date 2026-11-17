<?php

namespace App\Support;

final class ShieldPermission
{
    public static function make(string $ability, string $subject): string
    {
        return str($ability)->studly()->toString().':'.str($subject)->studly()->toString();
    }

    /**
     * @param  list<string>  $abilities
     * @return list<string>
     */
    public static function forSubject(string $subject, array $abilities): array
    {
        return array_map(
            fn (string $ability): string => self::make($ability, $subject),
            $abilities,
        );
    }
}
