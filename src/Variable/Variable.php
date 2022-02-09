<?php
namespace Clownerie\ClownView\Variable;

class Variable
{
    private array $variables;
    private array $paths;
    private array $temp = [];

    public function __construct(array $variables)
    {
        $this->variables = $variables;

        foreach (array_merge([], $this->variables) as $key => $value) {
            $this->generate($value);
        }
    }

    private function generate(array $var, string $currentpath = "", $arrayindex = 0)
    {
        foreach ($var as $key => $value) {
            if (is_string($key)) {
                if (empty($currentpath)) {
                    $currentpath = $key;
                } else {
                    $currentpath = $currentpath . ".".$key;
                }
            } else {
                if (empty($currentpath)) {
                    $currentpath = $key;
                } else {
                    $i = $arrayindex - 1;
                    if ($i < 0) {
                        $i = 0;
                    } else {
                        $i = $arrayindex;
                    }
                    if ($i > 0) {
                        $t = substr_replace($currentpath, $i, strlen($currentpath) - strlen($i));
                        $currentpath = $t;
                    } else {
                        $currentpath = $currentpath . ".".$arrayindex;
                    }
                }
            }
            

            if (is_array($value)) {
                $this->generate($value, $currentpath);
            } else {
                $this->paths[$currentpath] = $value;
            }
            $arrayindex++;
        }
    }
}
