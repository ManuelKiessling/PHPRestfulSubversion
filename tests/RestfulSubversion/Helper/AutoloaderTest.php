<?php

namespace RestfulSubversion\Helper;

class AutoloaderTest extends \PHPUnit_Framework_TestCase
{
    public function test_existingFile()
    {
        $this->assertSame('Core/RepoCache.php', Autoloader::load('RestfulSubversion_Core_RepoCache'));
    }

    public function test_nonExistantFile()
    {
        $this->assertFalse(Autoloader::load('dewdew.php'));
    }
}
