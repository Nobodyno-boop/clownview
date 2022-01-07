<?php

namespace Clownerie\ClownView\Lexer\tokens;

use Clownerie\ClownView\Parser\Parser;

class VariableToken extends AbstractToken
{

    public function execute(Parser $parser): void
    {

    }

    public function tag(): string
    {
        return "";
    }
}