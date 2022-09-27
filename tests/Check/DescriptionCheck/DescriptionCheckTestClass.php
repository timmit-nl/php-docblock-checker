<?php

namespace TiMMiT\PhpDocBlockChecker\Tests\Check\DescriptionCheck;

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

    /** One line comment */
    public function oneLineDescription()
    {
    }
}
