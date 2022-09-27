<?php

namespace TiMMiT\PhpDocBlockChecker\DocblockParser;

/**
 * Class DocblockParser
 * @package PhpDocBlockChecker\DocblockParser
 */
class DocblockParser
{
    public const DESCRIPTION_TAG_LINE = '_descriptionLine';

    /**
     * Parse the comment into the component parts and set the state of the object.
     * @param string $comment The docblock
     * @return TagCollection
     */
    public function parseComment($comment)
    {
        $tags = new TagCollection;

        preg_match_all('/\*\s+(\w+.*)\s*/', $comment, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            array_shift($match);

            $matchBody = \trim($match[0]);

            if ('*/' === \substr($matchBody, -2)) {
                $matchBody = \trim(\substr($matchBody, 0, -2));
            }

            $tags->addTag($this->getTagEntity(self::DESCRIPTION_TAG_LINE, $matchBody));
        }

        preg_match_all('/@([a-zA-Z]+) *(.*)\s*/', $comment, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            array_shift($match);
            list($tag, $body) = $match;

            if ('*/' === \substr($body, -2)) {
                $body = \trim(\substr($body, 0, -2));
            }

            $tags->addTag($this->getTagEntity(\strtolower($tag), $body));
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

        if ($tag === self::DESCRIPTION_TAG_LINE) {
            return new DescriptionTag($body);
        }

        return new Tag($tag, $body);
    }
}
