<?php

namespace TiMMiT\PhpDocBlockChecker\Check;

use TiMMiT\PhpDocBlockChecker\FileInfo;
use TiMMiT\PhpDocBlockChecker\Config\Config;
use TiMMiT\PhpDocBlockChecker\Status\FileStatus;
use TiMMiT\PhpDocBlockChecker\Status\StatusType\Passed\Passed;

class Checker
{
    /**
     * @var Config
     */
    private $config;

    private $checks = [
        ClassCheck::class,
        MethodCheck::class,
        ParamCheck::class,
        ReturnCheck::class,
        DescriptionCheck::class,
    ];

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @param FileInfo $fileInfo
     * @return FileStatus
     */
    public function check(FileInfo $fileInfo)
    {
        $fileStatus = new FileStatus();
        foreach ($this->checks as $check) {
            /** @var CheckInterface $check */
            $check = new $check($this->config, $fileStatus);
            if ($check->enabled()) {
                $check->check($fileInfo);
            }
        }

        if (!$fileStatus->hasErrors()) {
            $fileStatus->add(new Passed($fileInfo->getFileName()));
        }

        return $fileStatus;
    }
}
