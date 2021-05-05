<?php declare(strict_types=1);
namespace EPKTechnologies\EPKBundle\Utils;

use JetBrains\PhpStorm\Pure;
use Ramsey\Uuid\UuidInterface;

/**
 * Base62 (0-9a-zA-Z) encoder/decoder
 *
 * @author Jan Egert <jan.egert@epk-technologies.com>
 */
class Base62Encoder
{
    public const INT64_MAX_LENGTH = 12;
    public const MD5_MAX_LENGTH = 22;
    public const UUID_MAX_LENGTH = 22;
    public const SHA1_MAX_LENGTH = 27;

    #[Pure]
    public static function encodeInt(int $input): string
    {
        return BaseConverter::encodeInt($input, BaseConverter::BASE_62);
    }

    #[Pure]
    public static function decodeInt(string $encoded_int): int
    {
        return BaseConverter::decodeInt($encoded_int, BaseConverter::BASE_62);
    }

    #[Pure]
    public static function encodeHex(int|string $hex_input): string
    {
        return BaseConverter::encodeHex($hex_input, BaseConverter::BASE_62);
    }

    #[Pure]
    public static function decodeHex(string $encoded_hex, int $output_length = null): string
    {
        return BaseConverter::decodeHex($encoded_hex, BaseConverter::BASE_62, $output_length);
    }

    public static function encodeUUID(UuidInterface $uuid): string
    {
        return BaseConverter::encodeUUID($uuid, BaseConverter::BASE_62);
    }

    public static function decodeUUID(string $encoded_uuid): UuidInterface
    {
        return BaseConverter::decodeUUID($encoded_uuid, BaseConverter::BASE_62);
    }
}
