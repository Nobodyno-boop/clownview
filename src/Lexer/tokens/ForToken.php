<?php

namespace Clownerie\ClownView\Lexer\tokens;

use Clownerie\ClownView\Parser\Parser;

class ForToken extends AbstractToken
{

    public function execute(Parser $parser): void
    {
//        dump(spl_object_id($this));
//        dump($parser->getLine());
        $start = $parser->getCursor();
        $arr = $parser->getToken();
        $str = trim(str_replace(["for", "in"], "", $arr["var"]));
        $ex = explode(" ", $str);
        $varname = $ex[count($ex)-1];

        $var = $parser->getVariables()["$varname"];


        $end = $parser->nextToken("endfor");
        $end['islink'] = $start;
        $parser->updateToken($end, $end['cursor']);
        $block = $parser->createBlock($start+1, $end["cursor"]- $start - 1);

        $ch = [];

        foreach ($var as $v) {
            $item = $v[0];
            $clone = array_merge([], $block);
            $blo = array_map(function ($value) use ($item,  $parser){
                if(is_array($value)){
                    $value["content"] = str_replace($value["content"], $item, $value["content"]);
                    return $value;
                }
                return $value;
            }, $clone);
            $ch[] = $blo;
        }

        $parser->replaceToken($start+1, count($block), $ch);

    }

    public function tag(): string
    {
        return "for";
    }
}