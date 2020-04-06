<?php

namespace PhpDocBlockChecker\Tests\Check\ClassCheck;

use PhpParser\ParserFactory;
use PhpDocBlockChecker\Config\Config;
use PhpDocBlockChecker\Check\ClassCheck;
use PhpDocBlockChecker\Status\FileStatus;
use PhpDocBlockChecker\FileParser\FileParser;
use PhpDocBlockChecker\DocblockParser\DocblockParser;

class ClassCheckTest extends \PHPUnit_Framework_TestCase
{
    /** @var ClassCheck */
    private $classCheck;

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

        $this->classCheck = new ClassCheck(Config::fromArray([]), $this->fileStatus);
    }

    public function testClassDocblock()
    {
        $filePath = __DIR__ . \DIRECTORY_SEPARATOR . 'ClassCheckTestClass.php';

        $this->classCheck->check($this->fileParser->parseFile($filePath));

        $this->assertFalse($this->fileStatus->hasErrors());
        $this->assertFalse($this->fileStatus->hasWarnings());
    }

    public function testClassDocblockFail()
    {
        $filePath = __DIR__ . \DIRECTORY_SEPARATOR . 'ClassCheckFailTestClass.php';

        $this->classCheck->check($this->fileParser->parseFile($filePath));

        $this->assertTrue($this->fileStatus->hasErrors());
    }
}
