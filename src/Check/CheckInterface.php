<?php

namespace TiMMiT\PhpDocBlockChecker\Check;

use TiMMiT\PhpDocBlockChecker\FileInfo;

interface CheckInterface
{
    /**
     * @param FileInfo $file
     */
    public function check(FileInfo $file);

    /**
     * @return bool
     */
    public function enabled();
}
