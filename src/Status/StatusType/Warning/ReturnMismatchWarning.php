<?php

namespace TiMMiT\PhpDocBlockChecker\Status\StatusType\Warning;

class ReturnMismatchWarning extends Warning
{
    /**
     * @var string
     */
    private $returnType;
    /**
     * @var string
     */
    private $docType;

    /**
     * ReturnMismatchWarning constructor.
     * @param string $file
     * @param string $class
     * @param int $line
     * @param string $method
     * @param string $returnType
     * @param string $docType
     */
    public function __construct($file, $class, $line, $method, $returnType, $docType)
    {
        parent::__construct($file, $class, $line);
        $this->method = $method;
        $this->returnType = $returnType;
        $this->docType = $docType;

        $this->methodName = \explode('::', $method)[1];
    }

    /**
     * @return string
     */
    public function getType()
    {
        return 'return-mismatch';
    }

    /**
     * @return string
     */
    public function getReturnType()
    {
        return $this->returnType;
    }

    /**
     * @return string
     */
    public function getDocType()
    {
        return $this->docType;
    }

    /**
     * @return string
     */
    public function getDecoratedMessage()
    {
        return parent::getDecoratedMessage() . '<info>' . $this->method .
            '</info> - @return <fg=blue>' . $this->docType .
            '</> does not match method signature (' . $this->returnType . ').';
    }
}
