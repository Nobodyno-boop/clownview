<?php

namespace Clownerie\ClownView\Block;

use Clownerie\ClownView\Lexer\Token\AbstractToken;
use Clownerie\ClownView\Lexer\Token\VariableToken;

class MergedBlock implements BlockType
{
    private string $source;
    private string $line;
    private string $tag;
    private AbstractToken $type;
    private int $start;
    private int $end;
    /** @var BlockType[]  */
    private array $blocks;

    /**
     * @param string $source
     * @param string $line
     * @param string $tag
     * @param AbstractToken $type
     * @param int $start
     * @param int $end
     * @param BlockType[] $blocks
     */
    public function __construct(string $source, string $line, string $tag, AbstractToken $type, int $start, int $end, array $blocks)
    {
        $this->source = $source;
        $this->line = $line;
        $this->tag = $tag;
        $this->type = $type;
        $this->start = $start;
        $this->end = $end;
        $this->blocks = $blocks;
    }


    /**
     * @return string
     */
    public function getSource(): string
    {
        return $this->source;
    }

    /**
     * @return string
     */
    public function getLine(): string
    {
        return $this->line;
    }

    /**
     * @return string
     */
    public function getTag(): string
    {
        return $this->tag;
    }

    /**
     * @return AbstractToken
     */
    public function getType(): AbstractToken
    {
        return $this->type;
    }

    /**
     * @return int
     */
    public function getStart(): int
    {
        return $this->start;
    }

    /**
     * @return int
     */
    public function getEnd(): int
    {
        return $this->end;
    }

    /**
     * @return BlockType[]
     */
    public function getBlocks(): array
    {
        return $this->blocks;
    }
}
