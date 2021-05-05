<?php declare(strict_types=1);
namespace EPKTechnologies\EPKBundle\Tests\Utils;

use EPKTechnologies\EPKBundle\Utils\BaseConverter;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * @author Jan Egert <jan.egert@epk-technologies.com>
 */
class BaseConverterTest extends TestCase
{
    function testIntEncoding()
    {
        $this->assertEquals('1LY7VK', BaseConverter::encodeInt(1234567890, BaseConverter::BASE_62));
        $this->assertSame(1234567890, BaseConverter::decodeInt('1LY7VK', BaseConverter::BASE_62));

        $this->assertEquals('ff', BaseConverter::encodeInt(255, BaseConverter::BASE_16));
        $this->assertSame(255, BaseConverter::decodeInt('ff', BaseConverter::BASE_16));
    }



    function testHexEncoding()
    {
        $this->assertEquals('KfbLh', BaseConverter::encodeHex('1234abcd', BaseConverter::BASE_62));
        $this->assertEquals('KfbLh', BaseConverter::encodeHex(0x1234abcd, BaseConverter::BASE_62));
        $this->assertEquals('1234abcd', BaseConverter::decodeHex('KfbLh', BaseConverter::BASE_62));
        $this->assertSame('001234abcd', BaseConverter::decodeHex('KfbLh', BaseConverter::BASE_62, 10));
    }

    function testUuidEncoding()
    {
        $uuid = Uuid::fromString('a8cfc899-4b7c-482f-bca0-d1a4643af3fb');
        $this->assertEquals('58XfQwS8i5A7LyV2JOtJcJ', BaseConverter::encodeUUID($uuid, BaseConverter::BASE_62));
        $this->assertEquals($uuid, BaseConverter::decodeUUID('58XfQwS8i5A7LyV2JOtJcJ', BaseConverter::BASE_62));
    }
}
