<?php declare(strict_types=1);
namespace EPKTechnologies\EPKBundle\Tests\Utils;

use EPKTechnologies\EPKBundle\Utils\EmailHashGenerator;
use PHPUnit\Framework\TestCase;

/**
 * @author Jan Egert <jan.egert@epk-technologies.com>
 */
class EmailHashGeneratorTest extends TestCase
{
    function testGenerateEmailHash()
    {
        $this->assertEquals('59kaz33x9rtqvkl6ncnxmopi9q0pdut', EmailHashGenerator::generateEmailHash('some.long.email.at.some@strange.domain.tld'));
        $this->assertEquals('59kaz33x9rtqvkl6ncnxmopi9q0pdut', EmailHashGenerator::generateEmailHash('Some.Long.Email.At.Some@Strange.Domain.Tld'));
        $this->assertLessThanOrEqual(EmailHashGenerator::MAX_HASH_LENGTH, strlen('59kaz33x9rtqvkl6ncnxmopi9q0pdut'));
    }
}
