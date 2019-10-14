<?php

namespace Bhittani\Filesystem;

use PHPUnit\Framework\TestCase;

class FilesystemTest extends TestCase
{
    var $fs;
    var $fixtures = __DIR__.'/fixtures';
    var $temp = __DIR__.'/fixtures/temp';

    function setUp()
    {
        $this->fs = new Filesystem;
    }

    function tearDown()
    {
        if ($this->fs->exists($this->temp)) {
            $this->fs->remove($this->temp);
        }
    }

    /** @test */
    function it_gets_file_contents()
    {
        $file = $this->fixtures.'/file.txt';

        $contents = $this->fs->getContents($file);

        $this->assertEquals('File content [foo]', $contents);
    }

    /** @test */
    function getContents_accepts_a_payload()
    {
        $file = $this->fixtures.'/file.txt';

        $contents = $this->fs->getContents($file, ['foo' => 'bar']);

        $this->assertEquals('File content bar', $contents);
    }

    /** @test */
    function it_dumps_content_to_a_file()
    {
        $file = $this->temp.'/dump.txt';

        $this->fs->dump($file, 'content');

        $this->assertEquals('content', file_get_contents($file));
    }

    /** @test */
    function dump_accepts_a_payload()
    {
        $file = $this->temp.'/dump.txt';

        $this->fs->dump($file, 'content [foo] [arr]', ['foo' => 'bar', 'arr' => [1, 2]]);

        $this->assertEquals(
            "content bar [\n    1,\n    2,\n]",
            file_get_contents($file)
        );
    }

    /** @test */
    function dump_accepts_a_callable_payload()
    {
        $file = $this->temp.'/dump.txt';

        $this->fs->dump($file, 'content [foo] [arr]', function ($content) {
            return ['foo' => $content, 'arr' => [1, 2]];
        });

        $this->assertEquals(
            "content content [foo] [\n    1,\n    2,\n] [\n    1,\n    2,\n]",
            file_get_contents($file)
        );
    }

    /** @test */
    function it_dumps_a_directory()
    {
        $dest = $this->temp.'/foo';
        $src = $this->fixtures.'/foo';

        $this->fs->dump($dest, $src);

        $this->assertFileEquals($src.'/foo.txt', $dest.'/foo.txt');
        $this->assertFileEquals($src.'/bar/bar.txt', $dest.'/bar/bar.txt');
    }

    /** @test */
    function directory_dump_accepts_a_payload()
    {
        $dest = $this->temp.'/foo';
        $src = $this->fixtures.'/foo';

        $this->fs->dump($dest, $src, ['foo' => 'fizz', 'bar' => 'buzz']);

        $this->assertEquals('Foo fizz', file_get_contents($dest.'/foo.txt'));
        $this->assertEquals('Bar buzz', file_get_contents($dest.'/bar/bar.txt'));
    }

    /** @test */
    function directory_dump_accepts_a_callable_payload()
    {
        $dest = $this->temp.'/foo';
        $src = $this->fixtures.'/foo';

        $this->fs->dump($dest, $src, function ($file) {
            return ['foo' => $file, 'bar' => $file];
        });

        $this->assertEquals('Foo '.$dest.'/foo.txt', file_get_contents($dest.'/foo.txt'));
        $this->assertEquals('Bar '.$dest.'/bar/bar.txt', file_get_contents($dest.'/bar/bar.txt'));
    }
}
