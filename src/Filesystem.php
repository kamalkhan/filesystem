<?php

/*
 * This file is part of bhittani/filesystem.
 *
 * (c) Kamal Khan <shout@bhittani.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Bhittani\Filesystem;

use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem as SymfonyFilesystem;

class Filesystem extends SymfonyFilesystem
{
    /**
     * Dump a file or a directory.
     *
     * @param string                $path
     * @param string|array|callable $pathOrContentsOrPayload
     * @param array|callable        $payload
     */
    public function dump($path, $pathOrContentsOrPayload, $payload = [])
    {
        if (! is_string($pathOrContentsOrPayload)
            && (is_array($pathOrContentsOrPayload)
                || is_callable($pathOrContentsOrPayload)
            )
        ) {
            $this->inject($path, $pathOrContentsOrPayload);
        } elseif (@is_dir($pathOrContentsOrPayload)) {
            if ($path !== $pathOrContentsOrPayload) {
                $this->mirror(
                    $pathOrContentsOrPayload,
                    $path,
                    null,
                    ['override' => true]
                );
            }

            $this->inject($path, $payload);
        } else {
            try {
                if ($this->exists($pathOrContentsOrPayload)) {
                    $this->dumpFile(
                        $path,
                        $this->getContents($pathOrContentsOrPayload, $payload)
                    );
                } else {
                    $this->dumpFile(
                        $path,
                        $this->mergePayload($pathOrContentsOrPayload, $payload)
                    );
                }
            } catch (IOException $e) {
                $this->dumpFile(
                    $path,
                    $this->mergePayload($pathOrContentsOrPayload, $payload)
                );
            }
        }
    }

    /**
     * Inject a payload.
     *
     * @param string         $path
     * @param array|callable $payload
     */
    public function inject($path, $payload)
    {
        if (is_file($path)) {
            $this->dump($path, $path, $payload);
        } else {
            $this->each($path, function ($file) use ($payload) {
                $this->inject($file->getRealPath(), $payload);
            });
        }
    }

    /**
     * Traverse every file in a directory.
     *
     * @param string   $directory
     * @param callable $callback
     */
    public function each($directory, callable $callback)
    {
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(
                $directory,
                RecursiveDirectoryIterator::SKIP_DOTS
            )
        );

        foreach ($files as $file) {
            $callback($file);
        }
    }

    /**
     * Get file contents.
     *
     * @param string         $filepath
     * @param array|callable $payload
     *
     * @return string
     */
    public function getContents($filepath, $payload = [])
    {
        if (is_callable($payload)) {
            $payload = $payload($filepath);
        }

        return $this->mergePayload(file_get_contents($filepath), $payload);
    }

    /**
     * Merge payload.
     *
     * @param string         $content
     * @param array|callable $payload
     *
     * @return string
     */
    protected function mergePayload($content, $payload = [])
    {
        if (is_callable($payload)) {
            $payload = $payload($content);
        }

        return str_replace(
            array_map(function ($key) {
                return "[{$key}]";
            }, array_keys($payload)),
            array_map(function ($value) {
                return is_array($value)
                    ? $this->stringifyArray($value)
                    : $value;
            }, array_values($payload)),
            $content
        );
    }

    /**
     * Convert an array to exported string.
     *
     * @param array $arr
     *
     * @return string
     */
    protected function stringifyArray(array $arr)
    {
        $str = var_export($arr, true);

        $str = preg_replace('/array \(/', '[', $str);
        $str = preg_replace('/\)/', ']', $str);
        $str = preg_replace('/\s*\[/', ' [', $str);
        $str = preg_replace('/\[\s*]/', '[]', $str);
        $str = preg_replace('/ {2}/', '    ', $str);
        $str = preg_replace('/\d => /', '', $str);

        return trim($str);
    }
}
