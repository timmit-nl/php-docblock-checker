<?php

namespace PhpDocBlockChecker\Status\StatusType\Error;

class DescriptionError extends Error
{
    /**
     * @var string
     */
    private $method;

    /** @var string */
    private $methodName;

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

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getMethodName()
    {
        return $this->methodName;
    }

    public function getType()
    {
        return 'method';
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
