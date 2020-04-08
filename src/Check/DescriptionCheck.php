<?php

namespace PhpDocBlockChecker\Check;

use PhpDocBlockChecker\FileInfo;
use PhpDocBlockChecker\Status\StatusType\Info\DescriptionInfo;
use PhpDocBlockChecker\Status\StatusType\Error\DescriptionError;

class DescriptionCheck extends Check
{

    /**
     * @param FileInfo $file
     */
    public function check(FileInfo $file)
    {
        foreach ($file->getMethods() as $name => $method) {
            $treatAsError = true;
            if (false === $method['has_return'] &&
                $this->config->isOnlySignatures() &&
                (empty($method['params']) || 0 === count($method['params']))) {
                $treatAsError = false;
            }

            if (empty($method['docblock']['comment'])) {
                if (true === $treatAsError) {
                    $this->fileStatus->add(new DescriptionError($file->getFileName(), $name, $method['line'], $name));
                } else {
                    $this->fileStatus->add(new DescriptionInfo($file->getFileName(), $name, $method['line'], $name));
                }
            }
        }
    }

    /**
     * @return bool
     */
    public function enabled()
    {
        return !$this->config->isSkipClasses();
    }
}
