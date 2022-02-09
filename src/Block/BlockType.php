<?php

namespace Clownerie\ClownView\Block;

use Clownerie\ClownView\Lexer\Token\AbstractToken;
use Clownerie\ClownView\Lexer\Token\VariableToken;

interface BlockType
{
    /**
     * @return string
     */
    public function getSource(): string;

    /**
     * @return string
     */
    public function getLine(): string;

    /**
     * @return string
     */
    public function getTag(): string;

    /**
     * @return AbstractToken|VariableToken|mixed
     */
    public function getType(): mixed;

    /**
     * @return int
     */
    public function getStart(): int;

    /**
     * @return int
     */
    public function getEnd(): int;
}
