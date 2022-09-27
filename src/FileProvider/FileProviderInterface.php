<?php

namespace TiMMiT\PhpDocBlockChecker\FileProvider;

interface FileProviderInterface
{
    /**
     * @return \Traversable
     */
    public function getFileIterator();
}
