<?php

namespace Clownerie\ClownView\Parser;

use Clownerie\ClownView\Lexer\Source;
use Clownerie\ClownView\Lexer\tokens\AbstractToken;
use Clownerie\ClownView\Lexer\tokens\Tokens;

class Parser{

    //
    private array $tree = [];
    private int $cursor = 0;
    private Source $source;
    private array $tokenf = [];
    private array $block = [];
    private array $variables = [
        "items" => [
            [
                "item 1"
            ],
            [
                "item 2"
            ]
        ]
    ];

    public function __construct(Source $source){
        $lc = 0;
        foreach(preg_split("/((\r?\n)|(\r\n?))/", $source->getSource()) as $line){
            $re = '/\{(?:\{|\%)\s*(.*)(?:\}|\%)\}/';
            preg_match_all($re, $line, $matches, PREG_SET_ORDER, 0);
            if(!empty($matches)){
//                var_dump($lc);
                $this->tokenf[] = $lc;
                $str = explode(" ", $matches[0][1], 2);
                $this->tree[] = ["content" => $line,"var" => $matches[0][1],"token" =>Tokens::found($str[0]), "islink" => -1, "cursor" => $lc];
            } else {
                $this->tree[] = $line;
            }
            $lc++;
        }
        dump($this->tree);
        $this->eval();
//        dump($this->tree);

        echo 'print template';

        $this->print();


    }

    private function eval(){
        foreach ($this->tree as $item){
            if(is_array($item)){
                if(!empty($item["token"])){
                    $class = $item["token"][1];
                    $class->execute($this);
                }
            }
            $this->cursor++;
        }
    }

    public function print(){
        $str = "";
        foreach ($this->tree as $item){
            if(is_array($item)){
                $str .= $item["content"]."\n";
            } else{
                $str .= $item;
            }
        }

        echo $str;
    }

    public static function load(Source $source): Parser{
        return new Parser($source);
    }

    /**
     * @return int
     */
    public function getCursor(): int
    {
        return $this->cursor;
    }

    /**
     * @return Source
     */
    public function getSource(): Source
    {
        return $this->source;
    }


    public function diff(int $cursor = -1): bool{
        $cursor = $cursor == -1 ? $this->cursor : $cursor;
        return empty($this->tree[$cursor]);
    }

    public function getLine(): string{
        return $this->diff() ? $this->tree[$this->cursor] : $this->tree[$this->cursor]["content"];
    }

    /**
     * @return array
     */
    public function getToken(): array
    {
        return $this->tree[$this->getCursor()];
    }

    public function nextToken(string $name = "") : array {
        if(empty($name)){
            $arr = array_filter($this->tokenf, function ($item){
                return $this->cursor < $item;
            });

            return $arr;
        } else {
            $arr = array_filter($this->tokenf, function ($item){
                return $this->cursor < $item;
            });
            $v = [];
            foreach ($arr as $value){
                if(is_array($this->tree[$value])){
                    if($this->tree[$value]["var"] == $name){
                        $v = $this->tree[$value];
                        break;
                    }
                }
            }
            return $v;
        }

    }

    public function nextValidToken(): array {
        $arr = $this->nextToken();
        $v = [];
        foreach ($arr as $value){
            if(is_array($this->tree[$value])){
                if(!empty($this->tree[$value]["token"])){
                    $v = $this->tree[$value];
                    break;
                }
            }
        }

        return $v;
    }

    public function updateToken($data, int $cursor = -1){
        $cursor = $cursor == -1 ? $this->cursor : $cursor;
//        dump($cursor);
        if(!$this->diff($cursor)){
            $this->tree[$cursor] = $data;
        }
    }

    public function createBlock(int $start = -1, $end = -1){
        $start = $start == -1 ? $this->cursor : $start;
        $end = $end == -1 ? $this->cursor : $end;

        $slc = array_slice($this->tree, $start, $end);
        return $slc;
    }

    public function cloneTree():array{
        return array_merge([], $this->tree);
    }



    public function replaceToken($start, $length, $data){
        $i =1;
        $clone = array_merge([], $this->tree);
        $st = array_splice($clone, 0, count($this->tree)-$start);
        $cloneTree = $this->cloneTree();
        $nd = array_splice($cloneTree, $start + $length+1, $length);

        $nwarray = array_merge($st, ...$data);
        $nwarray = array_merge($nwarray, $nd);
        $this->tree = $nwarray;
    }

    /**
     * @return array
     */
    public function getVariables(): array
    {
        return $this->variables;
    }



}