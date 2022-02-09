<?php

namespace Clownerie\ClownView\Block;

use Clownerie\ClownView\Lexer\Lexer;
use Clownerie\ClownView\Lexer\Token\AbstractToken;
use Clownerie\ClownView\Lexer\Token\Tokens;
use Clownerie\ClownView\Lexer\Token\VariableToken;

class Block implements BlockType
{
    private string $source;
    private string $line;
    private string $tag;
    private AbstractToken $type;
    private int $start;
    private int $end;

    /**
     * @param string $source
     */
    public function __construct(string $source, $start, $end)
    {
        $this->source = $source;

        $this->line = trim(preg_replace("/\s+/", " ", $source));
        $tg = preg_replace(Lexer::start(), "", $this->line);
        $tg = preg_replace(Lexer::start(1), "", $tg);
        $exp = explode(" ", $tg);
        if (count($exp) >= 0) {
            if ($exp[0] !== "") {
                $this->tag = $exp[0];
            } else {
                $this->tag = $exp[1];
            }
        }
        $type = Tokens::found($this->getTag());
        $this->type = empty($type) ? new VariableToken() : $type[0];
        $this->start = $start;
        $this->end = $end;
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
     * @return AbstractToken|VariableToken|mixed
     */
    public function getType(): mixed
    {
        return $this->type;
    }

    /**
     * @param string $line
     */
    public function setLine(string $line): void
    {
        $this->line = $line;
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
     * @param string $source
     */
    public function setSource(string $source): void
    {
        $this->source = $source;
    }
}
