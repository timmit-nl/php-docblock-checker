<?php

namespace PhpDocBlockChecker\Tests\Check\ParamCheck;

class ParamCheckTestClass
{
    /**
     * test method
     */
    public function missingParam(int $i)
    {

    }

    /**
     * @param int|null $i
     */
    public function mismatchParam(int $i)
    {

    }

    /**
     * @param int $i
     */
    public function success(int $i)
    {

    }
}
