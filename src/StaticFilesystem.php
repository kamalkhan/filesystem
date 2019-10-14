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

use BadMethodCallException;

class StaticFilesystem
{
    /**
     * Invoke a method on the shared instance.
     *
     * @param string  $name
     * @param mixed[] $arguments
     *
     * @throws BadMethodCallException if the method does not exist on the instance
     *
     * @return mixed
     */
    public static function __callStatic($name, array $arguments)
    {
        static $instance;

        $instance = $instance ?: new Filesystem();

        if (method_exists($instance, $name)) {
            return $instance->{$name}(...$arguments);
        }

        throw new BadMethodCallException(sprintf(
            'Call to undefined method %s::%s().',
            get_class($instance),
            $name
        ));
    }
}
