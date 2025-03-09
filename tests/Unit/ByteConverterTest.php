<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use BadrQaba\DevBoost\Media\Core\ByteConverter;

//php artisan test --testsuite=Unit  --filter ByteConverterTest
class ByteConverterTest extends TestCase
{
    // We need to use the trait in a mock class for testing
    use ByteConverter;

    public function testToKilobytes()
    {
        $bytes = 1024;
        $result = $this->toKilobytes($bytes);
        $this->assertEquals(1.0, $result, '1 kilobyte should be equal to 1024 bytes');

        $bytes = 2048;
        $result = $this->toKilobytes($bytes);
        $this->assertEquals(2.0, $result, '2 kilobytes should be equal to 2048 bytes');
    }

    public function testToMegabytes()
    {
        $bytes = 1024 * 1024;
        $result = $this->toMegabytes($bytes);
        $this->assertEquals(1.0, $result, '1 megabyte should be equal to 1024 kilobytes');

        $bytes = 2 * 1024 * 1024;
        $result = $this->toMegabytes($bytes);
        $this->assertEquals(2.0, $result, '2 megabytes should be equal to 2048 kilobytes');
    }

    public function testToGigabytes()
    {
        $bytes = 1024 * 1024 * 1024;
        $result = $this->toGigabytes($bytes);
        $this->assertEquals(1.0, $result, '1 gigabyte should be equal to 1024 megabytes');

        $bytes = 2 * 1024 * 1024 * 1024;
        $result = $this->toGigabytes($bytes);
        $this->assertEquals(2.0, $result, '2 gigabytes should be equal to 2048 megabytes');
    }

    public function testToTerabytes()
    {
        $bytes = 1024 * 1024 * 1024 * 1024;
        $result = $this->toTerabytes($bytes);
        $this->assertEquals(1.0, $result, '1 terabyte should be equal to 1024 gigabytes');

        $bytes = 2 * 1024 * 1024 * 1024 * 1024;
        $result = $this->toTerabytes($bytes);
        $this->assertEquals(2.0, $result, '2 terabytes should be equal to 2048 gigabytes');
    }

    public function testToPetabytes()
    {
        $bytes = 1024 * 1024 * 1024 * 1024 * 1024;
        $result = $this->toPetabytes($bytes);
        $this->assertEquals(1.0, $result, '1 petabyte should be equal to 1024 terabytes');

        $bytes = 2 * 1024 * 1024 * 1024 * 1024 * 1024;
        $result = $this->toPetabytes($bytes);
        $this->assertEquals(2.0, $result, '2 petabytes should be equal to 2048 terabytes');
    }
}
