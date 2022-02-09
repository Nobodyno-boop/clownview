<?php

namespace Clownerie\ClownView\Lexer\Oldtokens;

use Clownerie\ClownView\Parser\OldParser;

abstract class AbstractToken
{
    abstract public function execute(OldParser $parser): void;

    abstract public function tag():string;
}
