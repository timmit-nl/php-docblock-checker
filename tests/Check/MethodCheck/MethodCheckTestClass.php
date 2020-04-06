<?php

namespace PhpDocBlockChecker\Tests\Check\MethodCheck;

class MethodCheckTestClass
{
    /**
     * @param string $s
     *
     * @return array
     */
    public function success(string $s): array
    {
        return [];
    }

    public function fail(int $i)
    {

    }
}
