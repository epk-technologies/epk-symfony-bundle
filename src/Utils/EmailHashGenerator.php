<?php declare(strict_types=1);
namespace EPKTechnologies\EPKBundle\Utils;

use JetBrains\PhpStorm\Pure;

/**
 * E-mails are by design case sensitive and quite long - up to 320 characters (@link https://tools.ietf.org/html/rfc3696).
 * This class creates shortened (base36 encoded / 0-9a-z characters) sha1 hash of normalized emails. Useful for e-mail indexing in DB.
 * Base36 encoding has been chosen because Doctrine ORM columns are case insensitive by default, so Base62 encoding could lead to collisions.
 *
 * @author Jan Egert <jan.egert@epk-technologies.com>
 */
class EmailHashGenerator
{
    public const MAX_HASH_LENGTH = Base36Encoder::SHA1_MAX_LENGTH;

    #[Pure]
    public static function generateEmailHash(string $email): string
    {
        return Base36Encoder::encodeHex(sha1(strtolower($email)));
    }
}