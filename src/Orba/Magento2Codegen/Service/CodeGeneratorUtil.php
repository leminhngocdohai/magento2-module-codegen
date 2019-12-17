<?php

namespace Orba\Magento2Codegen\Service;

use Orba\Magento2Codegen\Helper\IO;
use Symfony\Component\Filesystem\Filesystem;

class CodeGeneratorUtil
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var FilepathUtil
     */
    private $filepathUtil;

    public function __construct(Filesystem $filesystem, FilepathUtil $filepathUtil)
    {
        $this->filesystem = $filesystem;
        $this->filepathUtil = $filepathUtil;
    }

    public function getDestinationFilePath(string $filePath, ?string $rootDir): string
    {
        return $this->filepathUtil->getAbsolutePath(
            $this->filepathUtil->removeTemplateDirFromPath($filePath),
            $rootDir
        );
    }

    public function canCopyWithoutOverriding(string $filePath): bool
    {
        return !$this->filesystem->exists($filePath);
    }

    public function shouldMerge(string $filePath, IO $io): bool
    {
        return $io->confirm(
            sprintf('%s already exists, would you like to perform a merge?', $filePath),
            true
        );
    }

    public function shouldOverride(string $filePath, IO $io): bool
    {
        return $io->confirm(
            sprintf('%s already exists, would you like to overwrite it?', $filePath),
            false
        );
    }

    public function generateFileWithContent(string $filePath, string $fileContent): void
    {
        $this->filesystem->dumpFile($filePath, $fileContent);
    }
}