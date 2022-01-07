<?php

namespace Clownerie\ClownView\Lexer;

class Source
{
    private string $source;
    private string $name;
    private string $path;

    /**
     * @param string $source
     * @param string $name
     * @param string $path
     */
    public function __construct(string $source, string $name, string $path)
    {
        $this->source = $source;
        $this->name = $name;
        $this->path = $path;
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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }




}