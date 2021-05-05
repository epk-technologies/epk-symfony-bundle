<?php declare(strict_types=1);
namespace EPKTechnologies\EPKBundle\Tests\Utils;

use EPKTechnologies\EPKBundle\Utils\Base62Encoder;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * @author Jan Egert <jan.egert@epk-technologies.com>
 */
class Base62EncoderTest extends TestCase
{
    function testIntEncoding()
    {
        $this->assertEquals('1LY7VK', Base62Encoder::encodeInt(1234567890));
        $this->assertEquals(1234567890, Base62Encoder::decodeInt('1LY7VK'));
    }

    function testHexEncoding()
    {
        $this->assertEquals('KfbLh', Base62Encoder::encodeHex('1234abcd'));
        $this->assertEquals('KfbLh', Base62Encoder::encodeHex(0x1234abcd));
        $this->assertEquals('1234abcd', Base62Encoder::decodeHex('KfbLh'));
        $this->assertSame('001234abcd', Base62Encoder::decodeHex('KfbLh', 10));
    }

    function testUuidEncoding()
    {
        $uuid = Uuid::fromString('a8cfc899-4b7c-482f-bca0-d1a4643af3fb');
        $this->assertEquals('58XfQwS8i5A7LyV2JOtJcJ', Base62Encoder::encodeUUID($uuid));
        $this->assertEquals($uuid, Base62Encoder::decodeUUID('58XfQwS8i5A7LyV2JOtJcJ'));
    }
}
