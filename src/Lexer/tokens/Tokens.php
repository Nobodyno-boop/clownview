<?php

namespace Clownerie\ClownView\Lexer\tokens;

class Tokens
{
    public const tokens = [
        VariableToken::class,
        ForToken::class
    ];

    public static function found(string $tag){
        $new = [];
        $new = array_map(function ($class) {
            return new $class;
        }, self::tokens, $new);
        return array_filter($new, function ($t) use ($tag) {
            return $t->tag() == $tag;
        });
    }
}