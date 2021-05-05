<?php declare(strict_types=1);
namespace EPKTechnologies\EPKBundle\Utils;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * Utility for number base conversion - useful especially for shortening hex strings (uuid, hashes, tokens ... ).
 *
 * @author Jan Egert <jan.egert@epk-technologies.com>
 */
class BaseConverter
{
    public const BASE_10 = 10; // 0-9
    public const BASE_16= 16; // 0-9a-f

    public const BASE_36 = 36; // 0-9a-z
    public const BASE_62 = 62; // 0-9a-zA-Z

    private const UUID_LENGTH = 32;

    public static function encodeInt(int $input, int $output_base): string
    {
        $init = gmp_init($input, self::BASE_10);
        return gmp_strval($init,$output_base);
    }

    public static function decodeInt(string $encoded_int, int $encoded_base): int
    {
        $init = gmp_init($encoded_int,$encoded_base);
        return (int)gmp_strval($init);
    }

    public static function encodeHex(int|string $hex_input, int $output_base): string
    {
        $init = gmp_init($hex_input, self::BASE_16);
        return gmp_strval($init,$output_base);
    }

    public static function decodeHex(string $encoded_hex, int $encoded_base, int $output_length = null): string
    {
        $init = gmp_init($encoded_hex, $encoded_base);
        $decoded = gmp_strval($init, self::BASE_16);
        if($output_length !== null){
            $decoded = str_pad($decoded, $output_length, '0', STR_PAD_LEFT);
        }
        return $decoded;
    }

    public static function encodeUUID(UuidInterface $uuid, int $output_base): string
    {
        return self::encodeHex(str_replace('-', '', $uuid->toString()), $output_base);
    }

    public static function decodeUUID(string $encoded_uuid, int $encoded_base): UuidInterface
    {
        $decoded = array_reduce([20, 16, 12, 8], function ($uuid, $offset) {
            return substr_replace($uuid, '-', $offset, 0);
        }, self::decodeHex($encoded_uuid, $encoded_base, self::UUID_LENGTH));
        return Uuid::fromString($decoded);
    }
}
