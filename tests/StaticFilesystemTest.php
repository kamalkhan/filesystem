<?php

namespace Bhittani\Filesystem;

use BadMethodCallException;
use PHPUnit\Framework\TestCase;

class StaticFilesystemTest extends TestCase
{
    /** @test */
    function it_passes_through_to_the_underlying_shared_instance()
    {
        $file = __DIR__.'/fixtures/file.txt';

        $contents = StaticFilesystem::getContents($file, ['foo' => 'bar']);

        $this->assertEquals('File content bar', $contents);
    }

    /** @test */
    function it_throws_a_bad_method_call_exception_if_the_method_does_not_exist()
    {
        try {
            StaticFilesystem::method404();
        } catch (BadMethodCallException $e) {
            return $this->assertEquals(sprintf(
                'Call to undefined method %s::%s().',
                Filesystem::class,
                'method404'
            ), $e->getMessage());
        }

        $this->fail(sprintf(
            'Expected a %s exception to be thrown.',
            BadMethodCallException::class
        ));
    }
}
