<?php

namespace PhpDocBlockChecker\Tests\Check\ReturnCheck;

use stdClass;

class ReturnCheckTestClass
{
    public function returnMissing()
    {
        return [];
    }

    public function returnMissingWithReturnType(): array
    {
        return [];
    }

    /**
     * @return int
     */
    public function returnMismatchArrayInt()
    {
        return [];
    }

    /**
     * @return array
     */
    public function returnMismatchIntArray(): int
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
    public function returnSuccessIntNull(): ?int
    {
        return null;
    }

    /**
     * @return int[]
     */
    public function returnSuccessArray(): array
    {
        return [0];
    }

    /**
     * @return stdClass
     */
    public function returnSuccessStdclass(): stdClass
    {
        return new stdClass;
    }

    /**
     * Method return nothing
     */
    public function returnVoid(): void
    {

    }

    /**
     * Method return nothing
     *
     * @return void
     */
    public function returnVoidWithTag(): void
    {

    }

    /**
     * Method return nothing
     *
     * @return self
     */
    public function returnSelf(): self
    {
        return $this;
    }

    /**
     * Method return nothing
     *
     * @return $this
     */
    public function returnThis(): self
    {
        return $this;
    }

    /**
     * Method return nothing
     *
     * @return ReturnCheckTestClass
     */
    public function returnClassName(): self
    {
        return $this;
    }
}
