<?php

namespace TiMMiT\PhpDocBlockChecker\Tests\Check\ParamCheck;

use ReflectionClass;
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

        require $filePath;
        $reflection = new ReflectionClass('PhpDocBlockChecker\Tests\Check\ParamCheck\ParamCheckTestClass');

        $this->paramCheck->check($this->fileParser->parseFile($filePath));

        $expected = [
            'missingParam'  => ParamMissingWarning::class,
            'mismatchParam' => ParamMismatchWarning::class,
            'failNullable'  => ParamMismatchWarning::class,
            'failNullable2' => ParamMismatchWarning::class,
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
        $this->assertEmpty(array_diff(array_keys($expected), array_keys($actualFails)));
    }
}
