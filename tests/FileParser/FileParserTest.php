<?php

namespace PhpDocBlockChecker\FileParser;

use PhpParser\ParserFactory;
use PhpDocBlockChecker\DocblockParser\DocblockParser;

class FileParserTest extends \PHPUnit_Framework_TestCase
{

    public function testParseFile()
    {
        $fileParser = new FileParser(
            (new ParserFactory())->create(ParserFactory::PREFER_PHP7),
            new DocblockParser()
        );

        $filePath = __DIR__ . '/TestClass.php';

        $fileInfo = $fileParser->parseFile($filePath);

        $this->assertEquals($filePath, $fileInfo->getFileName());
        $class = $fileInfo->getClasses()['PhpDocBlockChecker\FileParser\TestClass'];

        $this->assertEquals('PhpDocBlockChecker\FileParser\TestClass', $class['name']);
        $this->assertEquals(null, $class['docblock']);

        $methodFoo = $fileInfo->getMethods()['PhpDocBlockChecker\FileParser\TestClass::foo'];

        $this->assertFalse($methodFoo['has_return']);
        $this->assertEmpty($methodFoo['params']);

        $methodBar = $fileInfo->getMethods()['PhpDocBlockChecker\FileParser\TestClass::bar'];
        $this->assertFalse($methodBar['has_return']);
        $this->assertEquals(['$foo' => null, '$bar' => null, '$baz' => null,], $methodBar['params']);

        $methodBaz = $fileInfo->getMethods()['PhpDocBlockChecker\FileParser\TestClass::baz'];
        $this->assertTrue($methodBaz['has_return']);
        $this->assertEmpty($methodBaz['params']);

        $methodDescription = $fileInfo->getMethods()['PhpDocBlockChecker\FileParser\TestClass::description'];
        $this->assertEquals('Test description', $methodDescription['docblock']['comment']);
        $this->assertTrue($methodDescription['has_return']);
        $this->assertEmpty($methodDescription['params']);

        $methodInheritDoc1 = $fileInfo->getMethods()['PhpDocBlockChecker\FileParser\TestClass::inheritDoc1'];
        $this->assertTrue($methodInheritDoc1['docblock']['inherit']);

        $methodInheritDoc2 = $fileInfo->getMethods()['PhpDocBlockChecker\FileParser\TestClass::inheritDoc2'];
        $this->assertTrue($methodInheritDoc2['docblock']['inherit']);
    }
}
