<?php

namespace TiMMiT\PhpDocBlockChecker\Tests\Check\ReturnCheck;

use ReflectionClass;
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

    public function testReturnCheck()
    {
        $filePath = __DIR__ . \DIRECTORY_SEPARATOR . 'ReturnCheckTestClass.php';

        require $filePath;
        $reflection = new ReflectionClass('PhpDocBlockChecker\Tests\Check\ReturnCheck\ReturnCheckTestClass');

        $this->returnCheck->check($this->fileParser->parseFile($filePath));

        $expected = [
            'returnMissing'               => ReturnMissingWarning::class,
            'returnMissingWithReturnType' => ReturnMissingWarning::class,
            'returnMismatchArrayInt'      => ReturnMismatchWarning::class,
            'returnMismatchIntArray'      => ReturnMismatchWarning::class,
            'returnMismatchNullable'      => ReturnMismatchWarning::class,
        ];

        $methods = $reflection->getMethods();

        $success = [];

        foreach ($methods as $method) {
            if (\in_array($method->getName(), \array_keys($expected))) {
                continue;
            }

            $success[] = $method->getName();
        }

        $actualFails = [];

        foreach ($this->fileStatus->getWarnings() as $warning) {
            $actualFails[$warning->getMethodName()] = \get_class($warning);
        }

        foreach ($success as $successMethod) {
            $this->assertFalse(
                \in_array($successMethod, \array_keys($actualFails)),
                "$successMethod found in actualFails array but must not"
            );
        }

        $this->assertFalse($this->fileStatus->hasErrors());
        $this->assertEmpty(array_merge(array_diff($expected, $actualFails), array_diff($actualFails, $expected)));
    }
}
