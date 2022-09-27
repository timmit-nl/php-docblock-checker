<?php

namespace TiMMiT\PhpDocBlockChecker\Status\StatusType\Error;

use TiMMiT\PhpDocBlockChecker\Status\StatusType\StatusType;

abstract class Error extends StatusType
{
    /**
     * @return string
     */
    public function getDecoratedMessage()
    {
        return '<fg=red>ERROR   </> ' . $this->file . ':' . $this->line . ' - ';
    }
}
