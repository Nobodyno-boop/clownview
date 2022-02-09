<?php

namespace Clownerie\ClownView\Lexer\Oldtokens;

class Tokens
{
    private static array $temp = [];
    public const tokens = [
        VariableToken::class,
        ForToken::class
    ];

    /**
     * @param string $tag
     * @return array
     */
    public static function found(string $tag): array
    {
        $new = self::$temp;

        if (empty($new)) {
            $new = array_map(function ($class) {
                return new $class;
            }, self::tokens, $new);
        }
        return array_filter($new, function ($t) use ($tag) {
            return $t->tag() == $tag;
        });
    }
}
