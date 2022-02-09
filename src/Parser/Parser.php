<?php

namespace Clownerie\ClownView\Parser;

use Clownerie\ClownView\Block\Block;
use Clownerie\ClownView\Block\BlockType;
use Clownerie\ClownView\Lexer\Lexer;
use Clownerie\ClownView\Lexer\Source;
use Clownerie\ClownView\Lexer\Token\VariableToken;
use Clownerie\ClownView\Variable\Variable;

class Parser
{
    /**
     * @var BlockType[]
     */
    private array $blocks;
    private int $curser = 0;
    private Source $source;
    private string $final;
    // $state
    private int $state;
    private array $variables;
    private block $currentBlock;
    private Variable $var;
    /**
     * @param Source $source
     */
    public function __construct(Source $source)
    {
        $this->source = $source;
        $this->final = $source->getSource();
    }


    public function load($variables)
    {
        $this->variables = $variables;
        $this->var = new Variable($variables);

        $this->__load();
        // dump($this->blocks);
        $this->__read();
        // dump($this->blocks);

        $this->__render();
    }

    public static function newInstance(Source $source)
    {
        return new Parser($source);
    }

    private function __load()
    {
        preg_match_all(Lexer::start(), $this->source->getSource(), $pos, PREG_OFFSET_CAPTURE);
        preg_match_all(Lexer::start(1), $this->source->getSource(), $epos, PREG_OFFSET_CAPTURE);
        $start = $pos[0];
        $end = $epos[0];
        if (count($start) === count($end)) {
            for ($i=0; $i < count($start); $i++) {
                $st = $start[$i];
                $en = $end[$i];

                $sub = substr($this->source->getSource(), $st[1], $en[1] - $st[1] +2);
                $block = new Block($sub, $st[1], $en[1]);

                $this->blocks[] = $block;
            }
        } else {
            throw new \Error($this->source->getSource());
        } // {{}} miss one of this
    }


    public function __read()
    {
        if (count($this->blocks) >=1) {
            foreach ($this->blocks as $block) {
                $this->currentBlock = $block;
                $block->getType()->execute($this);
                $this->curser++;
            }
        }
    }

    public function stream(): ParserStream
    {
        return new ParserStream($this);
    }

    /**
     * @return Block[]
     */
    public function getBlocks(): array
    {
        return $this->blocks;
    }

    /**
     * @return int
     */
    public function getCurser(): int
    {
        return $this->curser;
    }

    /**
     * @return Source
     */
    public function getSource(): Source
    {
        return $this->source;
    }

    /**
     * @return string
     */
    public function getFinal(): string
    {
        return $this->final;
    }

    /**
     * @return array
     */
    public function getVariables(): array
    {
        return $this->variables;
    }

    /**
     * @param BlockType[] $blocks
     */
    public function setBlocks(array $blocks): void
    {
        $this->blocks = $blocks;
    }

    /**
     * @return int
     */
    public function getState(): int
    {
        return $this->state;
    }

    /**
     * @return Block
     */
    public function getCurrentBlock(): Block
    {
        return $this->currentBlock;
    }

    private function __render()
    {
        $this->curser = 0;
        $blocks = $this->blocks;
        $txt = $this->getSource()->getSource();

        foreach ($blocks as $block) {
            $txt = $this->__replace($block, $txt);
            dump($txt);
        }
    }

    private function __replace(BlockType $blockType, string $text):string
    {
        if ($blockType->getType() instanceof VariableToken) {
            dump(strlen($text));
            dump($blockType->getStart());
            dump($blockType->getEnd());
            dump($blockType);
            $t = substr_replace($text, "", $blockType->getStart(), $blockType->getEnd() - $blockType->getStart() + 2);
            dump($t);
            // dump($blockType->getSource());
        }

        return $text;
    }
}
