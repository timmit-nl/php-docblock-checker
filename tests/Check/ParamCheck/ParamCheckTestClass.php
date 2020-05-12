<?php

namespace PhpDocBlockChecker\Tests\Check\ParamCheck;

use stdClass;

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

    /**
     * @param stdClass $p
     */
    public function failNullable(stdClass $p = null)
    {

    }

    /**
     * @param stdClass|null $p
     */
    public function failNullable2(stdClass $p)
    {

    }

    /**
     * @param stdClass|null $p
     */
    public function successNullable(stdClass $p = null)
    {

    }

    /**
     * @param stdClass|null $p
     */
    public function successNullable2(?stdClass $p)
    {

    }
}
