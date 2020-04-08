<?php

namespace PhpDocBlockChecker\DocblockParser;

class DescriptionTag extends Tag
{
    /**
     * DescriptionTag constructor.
     *
     * @param string $value
     */
    public function __construct($value)
    {
        parent::__construct(DocblockParser::DESCRIPTION_TAG_NME, $value);
    }
}
