<?php

namespace PhpDocBlockChecker\Status\StatusType\Error;

class DescriptionError extends Error
{
    /**
     * MethodError constructor.
     *
     * @param string $file
     * @param string $class
     * @param int    $line
     * @param string $method
     */
    public function __construct($file, $class, $line, $method)
    {
        parent::__construct($file, $class, $line);
        $this->method     = $method;
        $this->methodName = \explode('::', $method)[1];
    }

    public function getType()
    {
        return 'description';
    }

    /**
     * @return string
     */
    public function getDecoratedMessage()
    {
        return parent::getDecoratedMessage(
            ) . 'Method <info>' . $this->method . '</info> is missing a description line.';
    }
}
