<?php

namespace Clownerie\ClownView\Parser;

use Clownerie\ClownView\Block\Block;
use Clownerie\ClownView\Block\BlockType;
use Clownerie\ClownView\Lexer\Lexer;

class ParserStream
{
    private Parser $parser;
    /** @var BlockType[]  */
    private array $blockM;
    private int $cursor;
    /**
     * @param Parser $parser
     */
    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
        $this->cursor = $parser->getCurser();
        $this->blockM = array_replace([], $this->parser->getBlocks()); // clone block[]
    }

    /**
     * @param int $int
     * @return BlockType|mixed
     */
    public function getBlock(int $int = 0)
    {
        return $this->blockM[$this->cursor + $int];
    }

    /**
     * @return BlockType|mixed
     */
    public function nextBlock()
    {
        return $this->getBlock(1);
    }

    /**
     * @param $name
     * @return BlockType
     */
    public function findNextBlock($name): array
    {
        $cut = array_slice($this->blockM, $this->cursor);
        $f = array_filter($cut, function (BlockType $block) use ($name) {
            return $block->getTag() == $name;
        });
        $v = array_values($f);

        return count($v) >= 1 ? $v : [];
    }

    /**
     * @param $name
     * @return BlockType[]
     */
    public function findBlockBefore($name): array
    {
        $cut = array_slice($this->blockM, $this->cursor+1);
        $arr = [];
        foreach ($cut as $block) {
            if ($block->getTag() == $name) {
                return $arr;
            } else {
                $arr[] = $block;
            }
        }
        return $arr;
    }

    public function findHtmlBefore($name)
    {
        $start = $this->getBlock();
        $end = $this->findNextBlock($name);
        if (!empty($end)) {
            $end = $end[0];
            $str = substr($this->parser->getSource()->getSource(), $start->getStart(), $end->getEnd() - $start->getStart()+2);
            $blocks= $this->findBlock($str);
            if (count($blocks) >= 2) {
                $startb = $blocks[0];
                $endb = $blocks[count($blocks)-1];
                $str = substr_replace($str, "", $startb->getStart(), $startb->getEnd()+ 2);
                $ew = substr_replace($str, "", strlen($str) - ($endb->getEnd() - $endb->getStart() +2));
                return $ew;
            }
            return "";
        }
    }

    public function findHtml()
    {
    }


    /**
     * @param $source
     * @return BlockType[]
     */
    public function findBlock($source): array
    {
        $blocks = [];
        preg_match_all(Lexer::start(), $source, $pos, PREG_OFFSET_CAPTURE);
        preg_match_all(Lexer::start(1), $source, $epos, PREG_OFFSET_CAPTURE);
        $start = $pos[0];
        $end = $epos[0];
        if (count($start) === count($end)) {
            for ($i=0; $i < count($start); $i++) {
                $st = $start[$i];
                $en = $end[$i];

                $sub = substr($source, $st[1], $en[1] - $st[1] +2);
                $block = new Block($sub, $st[1], $en[1]);
                $blocks[] = $block;
            }
        } else {
            throw new \Error($source);
        } // {{}} miss one of this
        return $blocks;
    }

    public function pushBlockBetween(array $blocks, int $start, int $end)
    {
        $clone = array_merge([], $this->blockM);
        $start = array_splice($clone, 0, ($end - $start) -1);
        $endblock = array_splice($clone, $end);

        $newarr = array_merge($start, $blocks, $endblock);
        $this->blockM = $newarr;
    }


    /**
     * @return int
     */
    public function getCursor(): int
    {
        return $this->cursor;
    }

    public function findCursor($block): int
    {
        $i = 0;
        foreach ($this->blockM as $bl) {
            if ($bl === $block) {
                return $i;
            }
            $i++;
        }

        return $i - 1;
    }


    /**
     * @return BlockType[]
     */
    public function getBlocks(): array
    {
        return $this->blockM;
    }
}
