<?php

namespace PhpDocBlockChecker\Tests\Check\ParamCheck;

use PhpParser\ParserFactory;
use PhpDocBlockChecker\Config\Config;
use PhpDocBlockChecker\Check\ParamCheck;
use PhpDocBlockChecker\Status\FileStatus;
use PhpDocBlockChecker\FileParser\FileParser;
use PhpDocBlockChecker\DocblockParser\DocblockParser;
use PhpDocBlockChecker\Status\StatusType\Warning\ParamMissingWarning;
use PhpDocBlockChecker\Status\StatusType\Warning\ParamMismatchWarning;

class ParamCheckTest extends \PHPUnit_Framework_TestCase
{

    /** @var ParamCheck */
    private $paramCheck;

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

        $this->paramCheck = new ParamCheck(Config::fromArray([]), $this->fileStatus);
    }

    public function testParamCheck()
    {
        $filePath = __DIR__ . \DIRECTORY_SEPARATOR . 'ParamCheckTestClass.php';

        $this->paramCheck->check($this->fileParser->parseFile($filePath));

        $expected = [
            'missingParam'  => ParamMissingWarning::class,
            'mismatchParam' => ParamMismatchWarning::class,
        ];

        $success = [
            'success',
            'success2',
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
