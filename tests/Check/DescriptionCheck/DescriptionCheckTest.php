<?php

namespace PhpDocBlockChecker\Tests\Check\DescriptionCheck;

use ReflectionClass;
use PhpParser\ParserFactory;
use PhpDocBlockChecker\Config\Config;
use PhpDocBlockChecker\Status\FileStatus;
use PhpDocBlockChecker\FileParser\FileParser;
use PhpDocBlockChecker\Check\DescriptionCheck;
use PhpDocBlockChecker\DocblockParser\DocblockParser;
use PhpDocBlockChecker\Status\StatusType\Error\DescriptionError;

class DescriptionCheckTest extends \PHPUnit_Framework_TestCase
{

    /** @var DescriptionCheck */
    private $descriptionCheck;

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

        $this->descriptionCheck = new DescriptionCheck(Config::fromArray([]), $this->fileStatus);
    }

    public function testDescriptionCheck()
    {
        $filePath = __DIR__ . \DIRECTORY_SEPARATOR . 'DescriptionCheckTestClass.php';

        require $filePath;
        $reflection = new ReflectionClass('PhpDocBlockChecker\Tests\Check\DescriptionCheck\DescriptionCheckTestClass');

        $this->descriptionCheck->check($this->fileParser->parseFile($filePath));

        $expected = [
            'fail' => DescriptionError::class,
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

        foreach ($this->fileStatus->getErrors() as $error) {
            $actualFails[$error->getMethodName()] = \get_class($error);
        }

        $failsMethods = [];

        foreach ($this->fileStatus->getErrors() as $error) {
            /** @var $error DescriptionError */
            $failsMethods[] = $error->getMethodName();
        }

        foreach ($success as $successMethod) {
            $this->assertFalse(
                \in_array($successMethod, \array_keys($actualFails)),
                "$successMethod found in actualFails array but must not"
            );
        }

        $this->assertTrue($this->fileStatus->hasErrors());
        $this->assertEmpty(
            array_diff(array_keys($expected), array_keys($actualFails)),
            'Expected fails does not match to actual fails'
        );
    }
}
