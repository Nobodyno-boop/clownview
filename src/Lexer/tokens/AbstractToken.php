<?php

namespace Clownerie\ClownView\Lexer\tokens;

use Clownerie\ClownView\Parser\Parser;

abstract class AbstractToken {

    public abstract function execute(Parser $parser): void;

    public abstract function tag():string;
}