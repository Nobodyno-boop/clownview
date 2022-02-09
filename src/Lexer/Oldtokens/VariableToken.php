<?php

namespace Clownerie\ClownView\Lexer\Oldtokens;

use Clownerie\ClownView\Parser\OldParser;

class VariableToken extends AbstractToken
{
    public function execute(OldParser $parser): void
    {
    }

    public function tag(): string
    {
        return "";
    }
}
