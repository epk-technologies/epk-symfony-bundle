<?php declare(strict_types=1);
namespace EPKTechnologies\EPKBundle\Tests\Utils;

use EPKTechnologies\EPKBundle\Utils\Base36Encoder;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * @author Jan Egert <jan.egert@epk-technologies.com>
 */
class Base36EncoderTest extends TestCase
{
    function testIntEncoding()
    {
        $this->assertEquals('kf12oi', Base36Encoder::encodeInt(1234567890));
        $this->assertEquals(1234567890, Base36Encoder::decodeInt('kf12oi'));
    }

    function testHexEncoding()
    {
        $this->assertEquals('51uoct', Base36Encoder::encodeHex('1234abcd'));
        $this->assertEquals('51uoct', Base36Encoder::encodeHex(0x1234abcd));
        $this->assertEquals('1234abcd', Base36Encoder::decodeHex('51uoct'));
        $this->assertSame('001234abcd', Base36Encoder::decodeHex('51uoct', 10));
    }

    function testUuidEncoding()
    {
        $uuid = Uuid::fromString('a8cfc899-4b7c-482f-bca0-d1a4643af3fb');
        $this->assertEquals('9zsasl90nzkbuihsptevu9z3v', Base36Encoder::encodeUUID($uuid));
        $this->assertEquals($uuid, Base36Encoder::decodeUUID('9zsasl90nzkbuihsptevu9z3v'));
    }
}
