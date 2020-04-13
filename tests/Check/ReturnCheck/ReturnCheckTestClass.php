<?php

namespace PhpDocBlockChecker\Tests\Check\ReturnCheck;

use stdClass;

class ReturnCheckTestClass
{
    public function returnMissing()
    {
        return [];
    }

    public function returnMissing2(): array
    {
        return [];
    }

    /**
     * @return int
     */
    public function returnMismatch()
    {
        return [];
    }

    /**
     * @return array
     */
    public function returnMismatch2(): int
    {
        return 0;
    }

    /**
     * @return int
     */
    public function returnMismatchNullable(): ?int
    {
        return null;
    }

    /**
     * @return int|null
     */
    public function returnSuccess(): ?int
    {
        return null;
    }

    /**
     * @return int[]
     */
    public function returnSuccess2(): array
    {
        return [0];
    }

    /**
     * @return stdClass
     */
    public function returnSuccess3(): stdClass
    {
        return new stdClass;
    }

    /**
     * Method return nothing
     */
    public function returnVoid(): void
    {

    }
}
