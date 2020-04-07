<?php

namespace PhpDocBlockChecker\Tests\Check\ParamCheck;

/**
 * Class ParamCheckTestClass
 *
 * @package PhpDocBlockChecker\Tests\Check\ParamCheck
 */
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
     * @param     $r
     */
    public function success2(int $i, $r)
    {

    }
}
