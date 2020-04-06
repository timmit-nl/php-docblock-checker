<?php

namespace PhpDocBlockChecker\Tests\Check\MethodCheck;

use PhpParser\ParserFactory;
use PhpDocBlockChecker\Config\Config;
use PhpDocBlockChecker\Status\FileStatus;
use PhpDocBlockChecker\Check\MethodCheck;
use PhpDocBlockChecker\FileParser\FileParser;
use PhpDocBlockChecker\DocblockParser\DocblockParser;
use PhpDocBlockChecker\Status\StatusType\Error\MethodError;

class MethodCheckTest extends \PHPUnit_Framework_TestCase
{

    /** @var MethodCheck */
    private $methodCheck;

    /** @var FileParser */
    private $fileParser;

    /** @var FileStatus */
    private $fileStatus;

    public function setUp()
    {
        $this->fileParser = new FileParser(
            (new ParserFactory)->create(ParserFactory::PREFER_PHP7),
            new DocblockParser
        );

        $this->fileStatus = new FileStatus;

        $this->methodCheck = new MethodCheck(Config::fromArray([]), $this->fileStatus);
    }

    public function testClassDocblock()
    {
        $filePath = __DIR__ . \DIRECTORY_SEPARATOR . 'MethodCheckTestClass.php';

        $this->methodCheck->check($this->fileParser->parseFile($filePath));

        $failsMethods = [];

        foreach ($this->fileStatus->getErrors() as $error) {
            /** @var $error MethodError */
            $failsMethods[] = $error->getMethodName();
        }

        $this->assertFalse(\in_array('success', $failsMethods, true));
        $this->assertTrue(\in_array('fail', $failsMethods, true));
    }
}
