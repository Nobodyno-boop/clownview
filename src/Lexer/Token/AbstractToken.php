<?php

namespace Clownerie\ClownView\Lexer\Token;

use Clownerie\ClownView\Parser\Parser;

abstract class AbstractToken
{
    abstract public function execute(Parser $parser): void;

    /**
     * @return string[]
     */
    abstract public function tag(): array;

    abstract public function render(Parser $parser, string $final): string;
}
