<?php

namespace TiMMiT\PhpDocBlockChecker\Check;

use Exception;
use ReflectionMethod;
use TiMMiT\PhpDocBlockChecker\FileInfo;
use TiMMiT\PhpDocBlockChecker\Status\StatusType\Warning\ParamMismatchWarning;
use TiMMiT\PhpDocBlockChecker\Status\StatusType\Warning\ParamMissingWarning;

class ParamCheck extends Check
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

            foreach ($method['params'] as $param => $type) {
                if (!isset($method['docblock']['params'][$param])) {
                    $this->fileStatus->add(
                        new ParamMissingWarning($file->getFileName(), $name, $method['line'], $name, $param)
                    );
                    continue;
                }

                if (!empty($type)) {
                    $docBlockTypes = \explode('|', \preg_replace('/\([a-z]*(\|[a-z]*)*\)\[\]/', 'array', $method['docblock']['params'][$param]));
                    $methodTypes   = \explode('|', $type);

                    \sort($docBlockTypes);

                    if (!\in_array('null', $methodTypes)) {
                        try {
                            $explode          = \explode('::', $method['name']);
                            $class            = $explode[0];
                            $methodName       = $explode[1];
                            $reflectionMethod = new ReflectionMethod($class, $methodName);
                            $parameters       = $reflectionMethod->getParameters();
                            foreach ($parameters as $parameter) {
                                if ('$' . $parameter->getName() === $param) {
                                    if ($parameter->getType()->allowsNull()) {
                                        $methodTypes[] = 'null';
                                    }
                                }
                            }
                        } catch (Exception $ex) {
                        }
                    }
                    $methodTypes = \array_unique($methodTypes);
                    \sort($methodTypes);

                    foreach ($docBlockTypes as $k => $subType) {
                        if ('[]' === \substr($subType, -2)) {
                            if (!\in_array('array', $docBlockTypes)) {
                                $docBlockTypes[$k] = 'array';
                            } else {
                                unset($docBlockTypes[$k]);
                            }
                        }
                        if ('mixed' === $subType) {
                            if (!\in_array('null', $docBlockTypes)) {
                                $docBlockTypes[] = 'null';
                            }
                        }
                    }
                    \sort($docBlockTypes);

                    if ($docBlockTypes !== $methodTypes) {
                        $this->fileStatus->add(
                            new ParamMismatchWarning(
                                $file->getFileName(),
                                $name,
                                $method['line'],
                                $name,
                                $param,
                                $type,
                                $method['docblock']['params'][$param]
                            )
                        );
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
