<?php

namespace TiMMiT\PhpDocBlockChecker\Check;

use TiMMiT\PhpDocBlockChecker\FileInfo;
use TiMMiT\PhpDocBlockChecker\Status\StatusType\Warning\ReturnMissingWarning;
use TiMMiT\PhpDocBlockChecker\Status\StatusType\Warning\ReturnMismatchWarning;

class ReturnCheck extends Check
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

            if (!empty($method['return'])) {
                if (\is_string($method['return']) && 'void' === $method['return']) {
                    continue;
                }

                $className = '';

                if (!empty($method['class'])) {
                    $className = \basename(\str_replace('\\', '/', ($method['class'])));
                }

                if (empty($method['docblock']['return'])) {
                    $this->fileStatus->add(
                        new ReturnMissingWarning(
                            $file->getFileName(),
                            $name,
                            $method['line'],
                            $name
                        )
                    );
                    continue;
                }

                $selfAliases = [
                    'self',
                    '$this',
                    $className,
                ];

                if (\in_array($method['return'], $selfAliases)) {
                    if (\in_array($method['docblock']['return'], $selfAliases)) {
                        continue;
                    }
                }
                if (is_array($method['return'])) {
                    $docBlockTypes = \explode('|', \preg_replace('/\([a-z]*(\|[a-z]*)*\)\[\]/', 'array', $method['docblock']['return']));
                    foreach ($docBlockTypes as $k => $type) {
                        if ('[]' === \substr($type, -2)) {
                            if (!\in_array('array', $docBlockTypes)) {
                                $docBlockTypes[$k] = 'array';
                            } else {
                                unset($docBlockTypes[$k]);
                            }
                        }
                        if ('mixed' === $type) {
                            if (!\in_array('null', $docBlockTypes)) {
                                $docBlockTypes[] = 'null';
                            }
                        }
                    }
                    \sort($docBlockTypes);

                    if ($method['return'] !== $docBlockTypes) {
                        $this->fileStatus->add(
                            new ReturnMismatchWarning(
                                $file->getFileName(),
                                $name,
                                $method['line'],
                                $name,
                                \implode('|', $method['return']),
                                $method['docblock']['return']
                            )
                        );
                    }
                } else {
                    if ($method['docblock']['return'] !== $method['return']) {
                        if ('array' === $method['return'] && '[]' === \substr($method['docblock']['return'], -2)) {
                            // Do nothing because this is fine.
                        } else {
                            $this->fileStatus->add(
                                new ReturnMismatchWarning(
                                    $file->getFileName(),
                                    $name,
                                    $method['line'],
                                    $name,
                                    $method['return'],
                                    $method['docblock']['return']
                                )
                            );
                        }
                    }
                }
            }
        }
    }

    /**
     * @return bool
     */
    public function enabled()
    {
        return !$this->config->isSkipSignatures();
    }
}
