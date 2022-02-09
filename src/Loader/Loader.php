<?php

namespace Clownerie\ClownView\Loader;

use Clownerie\ClownView\Lexer\Source;
use Clownerie\ClownView\Parser\OldParser;
use Clownerie\ClownView\Parser\Parser;

class Loader
{
    private $config;

    /*
     * path => Source
     */
    private array $cache = [];


    /**
     * @param $config
     */
    public function __construct($config)
    {
        $this->config = $config;
    }


    public function loadView(string $name)
    {
        if (!str_contains($name, ".")) {
            $name = $name.".view";
        }
        $path = $this->config["path"].$name;
        if (file_exists($path)) {
            $str = file_get_contents($path);
            if (is_string($str)) {
                $source = new Source($str, $name, $path);
                $this->cache[] = $source;
                dump($this->cache);

                $parser = Parser::newInstance($source);
                $parser->load([
                    "zizi" => "caca",
                    "items" => [
                        ["id" => 1],
                        ["id" => 2],
                        ["id" => 3],
                        "type" => "tokens"
                    ]
                ]);
            } else {
                throw new \Exception("Cannot load the view ! path: {$path}");
            }
        } else {
            throw new \Exception("Cannot load the view ! path: {$path}");
        }
    }
}
