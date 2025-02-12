<?php

namespace TiMMiT\PhpDocBlockChecker\Check;

use TiMMiT\PhpDocBlockChecker\FileInfo;
use TiMMiT\PhpDocBlockChecker\Status\StatusType\Info\DescriptionInfo;
use TiMMiT\PhpDocBlockChecker\Status\StatusType\Error\DescriptionError;

class DescriptionCheck extends Check
{

    /**
     * @param FileInfo $file
     */
    public function check(FileInfo $file)
    {
        foreach ($file->getMethods() as $name => $method) {
            // If the docblock is inherited, we can't check for params and return types:
            if (isset($method['docblock']['inherit']) && $method['docblock']['inherit']) {
                continue;
            }

            $treatAsError = true;
            if (
                false === $method['has_return'] &&
                $this->config->isOnlySignatures() &&
                (empty($method['params']) || 0 === count($method['params']))
            ) {
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
