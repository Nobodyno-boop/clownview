<?php

namespace Clownerie\ClownView\Lexer\Token;

use Clownerie\ClownView\Parser\Parser;

class VariableToken extends AbstractToken
{
    public function execute(Parser $parser): void
    {
    }

    public function tag(): array
    {
        return [""];
    }

    public function render(Parser $parser, string $final): string
    {
        // TODO: Implement render() method.
        return "";
    }
}
