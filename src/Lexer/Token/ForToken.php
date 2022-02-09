<?php

namespace Clownerie\ClownView\Lexer\Token;

use Clownerie\ClownView\Block\BlockFor;
use Clownerie\ClownView\Lexer\Source;
use Clownerie\ClownView\Parser\Parser;

class ForToken extends AbstractToken
{
    public function execute(Parser $parser): void
    {
        if ($parser->getCurrentBlock()->getTag() === "for") {
            $stream = $parser->stream();
            $startblock = $stream->getBlock();
            $endblock = $stream->findNextBlock("endfor");
            $html = $stream->findHtmlBefore("endfor");

            $tmp = $stream->findBlock($html);
            $tmp = array_map(function ($block) use ($html) {
                $block->setSource($html);
                return $block;
            }, $tmp);


            $cur = $stream->findCursor($endblock);
            $cy = $endblock[0]->getEnd() - $startblock->getStart() + 2;
            $str = substr($parser->getSource()->getSource(), $startblock->getStart(), $cy);
            $for = new BlockFor($str, "unknow", "forMerge", $this, $startblock->getStart(), $endblock[0]->getEnd() +2, [$startblock, ...$tmp, ...$endblock]);
            $stream->pushBlockBetween([$for], $stream->getCursor(), $cur);
            $parser->setBlocks($stream->getBlocks());
        }
    }

    public function tag(): array
    {
        return ["for", "endfor"];
    }

    public function render(Parser $parser, string $final): string
    {
        // TODO: Implement render() method.
        return "";
    }
}
