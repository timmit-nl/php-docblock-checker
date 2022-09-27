<?php

namespace TiMMiT\PhpDocBlockChecker\Status\StatusType\Info;

use TiMMiT\PhpDocBlockChecker\Status\StatusType\StatusType;

abstract class Info extends StatusType
{
    /**
     * @return string
     */
    public function getDecoratedMessage()
    {
        return '<fg=blue>INFO   </> ' . $this->file . ':' . $this->line . ' - ';
    }
}
