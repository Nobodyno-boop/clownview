<?php

namespace Clownerie\ClownView;

use Clownerie\ClownView\Loader\Loader;

class ClownView
{
    private $config = [];
    private Loader $loader;
    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;

        if (!$this->verifyConfig()) {
            throw new \Exception("Config Error !");
        }

        $this->loader = new Loader($this->config);
    }


    private function verifyConfig() : bool
    {
        if (!array_key_exists("path", $this->config)) {
            return false;
        }

        return true;
    }

    /**
     * @throws \Exception
     */
    public static function newInstance(array $array) : ClownView
    {
        return new ClownView($array);
    }

    public function load(string $name)
    {
        try {
            $this->loader->loadView($name);
        } catch (\Exception $e) {
            die($e);
        }
    }
}
