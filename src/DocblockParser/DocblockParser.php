<?php

namespace PhpDocBlockChecker\DocblockParser;

/**
 * Class DocblockParser
 * @package PhpDocBlockChecker\DocblockParser
 */
class DocblockParser
{
    public const DESCRIPTION_TAG_NME = '_descriptionLine';

    /**
     * Parse the comment into the component parts and set the state of the object.
     * @param string $comment The docblock
     * @return TagCollection
     */
    public function parseComment($comment)
    {
        $tags = new TagCollection;

        preg_match_all('/\*\s+(\w+.*)\n/', $comment, $matches, PREG_SET_ORDER);
        foreach ($matches as $match) {
            array_shift($match);

            $tags->addTag($this->getTagEntity(self::DESCRIPTION_TAG_NME, $match[0]));
        }

        preg_match_all('/@([a-zA-Z]+) *(.*)\n/', $comment, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            array_shift($match);
            list($tag, $body) = $match;

            $tags->addTag($this->getTagEntity($tag, $body));
        }

        return $tags;
    }

    /**
     * @param string $tag
     * @param string $body
     * @return ParamTag|ReturnTag|Tag
     */
    private function getTagEntity($tag, $body)
    {
        if ($tag === 'param') {
            return new ParamTag($tag, $body);
        }

        if ($tag === 'return') {
            return new ReturnTag($tag, $body);
        }

        if ($tag === self::DESCRIPTION_TAG_NME) {
            return new DescriptionTag($body);
        }

        return new Tag($tag, $body);
    }
}
