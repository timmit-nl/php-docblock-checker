<?php

namespace PhpDocBlockChecker\FileParser;

class TestClass
{
    public function foo()
    {
    }

    public function bar($foo, $bar, $baz)
    {
    }

    public function baz()
    {
        return true;
    }

    /**
     * Test description
     *
     * @return bool
     */
    public function description()
    {
        return true;
    }
}
