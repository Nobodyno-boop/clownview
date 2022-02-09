<?php

namespace Clownerie\ClownView\Lexer;

class Lexer
{

    /**
     * @var array
     */
    private static array $option = [
    'tag_comment' => ['{#', '#}'],
    'tag_block' => ['{%', '%}'],
    'tag_variable' => ['{{', '}}'],
    'whitespace_trim' => '-',
    'whitespace_line_trim' => '~',
    'whitespace_line_chars' => ' \t\0\x0B',
    'interpolation' => ['#{', '}'],
    ];

    public static function start($i = 0)
    {
        return '{('.
            preg_quote(self::$option['tag_variable'][$i], '#'). // {{
            '|'.
            preg_quote(self::$option['tag_block'][$i], '#'). // {%
            '|'.
            preg_quote(self::$option['tag_comment'][$i], '#'). // {#
            ')('.
            preg_quote(self::$option['whitespace_trim'], '#'). // -
            '|'.
            preg_quote(self::$option['whitespace_line_trim'], '#'). // ~
            ')?}sx';
    }
}
