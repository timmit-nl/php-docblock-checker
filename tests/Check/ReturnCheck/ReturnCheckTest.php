<?php

namespace PhpDocBlockChecker\Tests\Check\ReturnCheck;

use PhpParser\ParserFactory;
use PhpDocBlockChecker\Config\Config;
use PhpDocBlockChecker\Status\FileStatus;
use PhpDocBlockChecker\Check\ReturnCheck;
use PhpDocBlockChecker\FileParser\FileParser;
use PhpDocBlockChecker\DocblockParser\DocblockParser;
use PhpDocBlockChecker\Status\StatusType\Warning\ReturnMissingWarning;
use PhpDocBlockChecker\Status\StatusType\Warning\ReturnMismatchWarning;

class ReturnCheckTest extends \PHPUnit_Framework_TestCase
{

    /** @var ReturnCheck */
    private $returnCheck;

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

        $this->returnCheck = new ReturnCheck(Config::fromArray([]), $this->fileStatus);
    }

    public function testClassDocblock()
    {
        $filePath = __DIR__ . \DIRECTORY_SEPARATOR . 'ReturnCheckTestClass.php';

        $this->returnCheck->check($this->fileParser->parseFile($filePath));

        $expected = [
            'returnMissing'          => ReturnMissingWarning::class,
            'returnMissing2'         => ReturnMissingWarning::class,
            'returnMismatch'         => ReturnMismatchWarning::class,
            'returnMismatch2'        => ReturnMismatchWarning::class,
            'returnMismatchNullable' => ReturnMismatchWarning::class,
        ];

        $success = [
            'returnSuccess',
            'returnSuccess2',
            'returnSuccess3',
        ];

        $actual = [];

        foreach ($this->fileStatus->getWarnings() as $warning) {
            $actual[$warning->getMethodName()] = \get_class($warning);
        }

        foreach ($success as $successMethod) {
            $this->assertFalse(\in_array($successMethod, \array_keys($actual)));
        }

        $this->assertFalse($this->fileStatus->hasErrors());
        $this->assertEmpty(array_merge(array_diff($expected, $actual), array_diff($actual, $expected)));
    }
}
