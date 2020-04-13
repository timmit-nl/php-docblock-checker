<?php

namespace PhpDocBlockChecker\Tests\Check\DescriptionCheck;

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

        $this->descriptionCheck->check($this->fileParser->parseFile($filePath));

        $failsMethods = [];

        foreach ($this->fileStatus->getErrors() as $error) {
            /** @var $error DescriptionError */
            $failsMethods[] = $error->getMethodName();
        }

        $this->assertFalse(\in_array('success', $failsMethods, true));
        $this->assertFalse(\in_array('successMultiline', $failsMethods, true));
        $this->assertTrue(\in_array('fail', $failsMethods, true));
    }
}
