<?php

namespace PhpDocBlockChecker\Tests\Check\DescriptionCheck;

class DescriptionCheckTestClass
{
    /**
     * Method description line
     */
    public function success()
    {

    }

    /**
     * Multiline
     * method
     * description
     */
    public function successMultiline()
    {

    }

    public function fail()
    {

    }
}
