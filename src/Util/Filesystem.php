<?php

/*
 * This file is part of the WP Starter package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace WeCodeMore\WpStarter\Util;

use Composer\Util\Filesystem as ComposerFilesystem;
use Composer\Util\Platform;

/**
 * Wrapper for Composer Filesystem with custom functionalities.
 */
class Filesystem
{
    /**
     * @var ComposerFilesystem
     */
    private $filesystem;

    /**
     * @param ComposerFilesystem $filesystem
     */
    public function __construct(ComposerFilesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * Save some textual content to a file in given path.
     *
     * @param string $content
     * @param string $targetPath
     * @return bool
     */
    public function save(string $content, string $targetPath): bool
    {
        $parent = dirname($this->filesystem->normalizePath($targetPath));

        if (!$this->createDir($parent)) {
            return false;
        }

        try {
            $exists = file_exists($targetPath);
            if ($exists && !is_file($targetPath)) {
                return false;
            }

            $currentContent = $exists ? file_get_contents($targetPath) : null;
            if ($currentContent === $content) {
                return true;
            }

            return file_put_contents($targetPath, $content) !== false;
        } catch (\Throwable $exception) {
            return false;
        }
    }

    /**
     * Move a single file from a source to a destination.
     *
     * @param string $sourcePath
     * @param string $targetPath
     * @return bool
     */
    public function moveFile(string $sourcePath, string $targetPath): bool
    {
        $sourcePath = $this->filesystem->normalizePath($sourcePath);
        if (!is_file($sourcePath)) {
            return false;
        }

        $targetPath = $this->filesystem->normalizePath($targetPath);
        if (!$this->createDir(dirname($targetPath))) {
            return false;
        }

        file_exists($targetPath) and $this->filesystem->unlink($targetPath);

        $this->filesystem->rename($sourcePath, $targetPath);

        return file_exists($targetPath);
    }

    /**
     * Copy a single file from a source to a destination.
     *
     * @param string $sourcePath
     * @param string $targetPath
     * @return bool
     */
    public function copyFile(string $sourcePath, string $targetPath): bool
    {
        $sourcePath = realpath($sourcePath);

        if (!is_file($sourcePath) || !$this->createDir(dirname($targetPath))) {
            return false;
        }

        try {
            return copy($sourcePath, $targetPath);
        } catch (\Throwable $exception) {
            return false;
        }
    }

    /**
     * Symlink implementation which uses junction on dirs on Windows.
     *
     * @param string $targetPath
     * @param string $linkPath
     * @return bool
     */
    public function symlink(string $targetPath, string $linkPath): bool
    {
        $isWindows = Platform::isWindows();
        $directories = is_dir($targetPath);

        try {
            if ($isWindows && $directories) {
                $this->filesystem->junction($targetPath, $linkPath);

                return $this->filesystem->isJunction($linkPath);
            }

            $absolute = $this->filesystem->isAbsolutePath($targetPath)
                && $this->filesystem->isAbsolutePath($linkPath);

            // Attempt relative symlink, but not on Windows
            if ($absolute && !$isWindows) {
                return $this->filesystem->relativeSymlink($targetPath, $linkPath);
            }

            return @symlink($targetPath, $linkPath);
        } catch (\Throwable $exception) {
            return false;
        }
    }

    /**
     * Recursively copy all files from a directory to another.
     *
     * @param string $sourcePath
     * @param string $targetPath
     * @return bool
     */
    public function moveDir(string $sourcePath, string $targetPath): bool
    {
        try {
            $sourcePath = $this->filesystem->normalizePath($sourcePath);
            if (!realpath($sourcePath) || !is_dir($sourcePath)) {
                return false;
            }

            $targetPath = $this->filesystem->normalizePath($targetPath);
            if (!$this->createDir($targetPath)) {
                return false;
            }

            $this->filesystem->copyThenRemove($sourcePath, $targetPath);
        } catch (\Throwable $exception) {
            return false;
        }

        return is_dir($targetPath) && !is_dir($sourcePath);
    }

    /**
     * Recursively copy all files from a directory to another.
     *
     * @param string $sourcePath
     * @param string $targetPath
     * @return bool
     */
    public function copyDir(string $sourcePath, string $targetPath): bool
    {
        $sourcePath = $this->filesystem->normalizePath($sourcePath);
        if (!realpath($sourcePath) || !is_dir($sourcePath)) {
            return false;
        }

        $targetPath = $this->filesystem->normalizePath($targetPath);
        $this->createDir($targetPath);

        return $this->filesystem->copy($sourcePath, $targetPath);
    }

    /**
     * Create a directory recursively, derived from wp_makedir_p.
     *
     * @param string $targetPath
     * @return bool
     */
    public function createDir(string $targetPath): bool
    {
        $targetPath = $this->filesystem->normalizePath($targetPath);

        if (file_exists($targetPath)) {
            return @is_dir($targetPath);
        }

        $parentDir = dirname($targetPath);
        while ('.' !== $parentDir && !is_dir($parentDir)) {
            $parentDir = dirname($parentDir);
        }

        $stat = @stat($parentDir);
        $permissions = $stat ? ((int)$stat['mode']) & 0007777 : 0755;

        if (!@mkdir($targetPath, $permissions, true) && !is_dir($targetPath)) {
            return false;
        }

        if ($permissions !== ($permissions & ~umask())) {
            $nameParts = explode('/', substr($targetPath, strlen($parentDir) + 1));
            for ($i = 1, $count = count($nameParts); $i <= $count; $i++) {
                $dirname = $parentDir . '/' . implode('/', array_slice($nameParts, 0, $i));
                @chmod($dirname, $permissions);
            }
        }

        return true;
    }

    /**
     * Remove a directory.
     *
     * @param string $directory
     * @return bool
     */
    public function removeRealDir(string $directory): bool
    {
        if ($this->isLink($directory)) {
            return false;
        }

        if (is_dir($directory)) {
            return $this->filesystem->removeDirectory($directory);
        }

        return !file_exists($directory);
    }

    /**
     * @param string $path
     * @return bool
     */
    public function isLink(string $path): bool
    {
        return $this->filesystem->isSymlinkedDirectory($path)
            || $this->filesystem->isJunction($path)
            || is_link($path);
    }
}
