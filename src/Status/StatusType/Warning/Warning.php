<?php

namespace TiMMiT\PhpDocBlockChecker\Status\StatusType\Warning;

use TiMMiT\PhpDocBlockChecker\Status\StatusType\StatusType;

abstract class Warning extends StatusType
{
    /**
     * @return string
     */
    public function getDecoratedMessage()
    {
        return '<fg=yellow>WARNING </> ';
    }
}
