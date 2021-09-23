<?php declare(strict_types=1);
namespace EPKTechnologies\EPKBundle\Tests\Utils;

use EPKTechnologies\EPKBundle\Utils\TextIdentifierGenerator;
use EPKTechnologies\EPKBundle\Utils\TextIdentifierGenerator\TextIdentifierConflictCheckInterface;
use EPKTechnologies\EPKBundle\Utils\TextIdentifierGenerator\TextIdentifierGeneratorException;
use PHPUnit\Framework\TestCase;

/**
 * @author Jan Egert <jan.egert@epk-technologies.com>
 */
class TextIdentifierGeneratorTest extends TestCase
{
    protected TextIdentifierGenerator $generator;

    protected function setUp(): void
    {
        $this->generator = new TextIdentifierGenerator();
    }


    function testGenerateIdentifier()
    {
        $phrase = 'Wôrķšƥáçè ~~sèťtïñğš~~';

        $this->assertEquals('workspace-settings', $this->generator->generateIdentifier($phrase));
        $this->generator->setMaxLength(10);
        $this->assertEquals('workspace', $this->generator->generateIdentifier($phrase));

        $checker = new class implements TextIdentifierConflictCheckInterface
        {
            public function __invoke(string $identifier, array $context = []): bool{
                return $identifier === 'workspace' || $identifier === 'workspace-settings';
            }
        };

        $this->generator->setExistenceChecker($checker);
        $this->generator->setMaxLength(9);
        $this->assertEquals('workspac1', $this->generator->generateIdentifier($phrase));

        $this->generator->setMaxLength(TextIdentifierGenerator::UNLIMITED_LENGTH);
        $this->assertEquals('workspace-settings1', $this->generator->generateIdentifier($phrase));
    }


    function testEmptyText()
    {
        $this->expectException(TextIdentifierGeneratorException::class);
        $this->expectExceptionCode(TextIdentifierGeneratorException::CODE_TOO_SHORT);
        $this->generator->generateIdentifier('');
    }

    function testWrongFormatText()
    {
        $this->expectException(TextIdentifierGeneratorException::class);
        $this->expectExceptionCode(TextIdentifierGeneratorException::CODE_TOO_SHORT);
        $this->generator->generateIdentifier(' - ');
    }

    function testTooShortText()
    {
        $checker = new class implements TextIdentifierConflictCheckInterface
        {
            public function __invoke(string $identifier, array $context = []): bool{
                return true;
            }
        };

        $this->expectException(TextIdentifierGeneratorException::class);
        $this->expectExceptionCode(TextIdentifierGeneratorException::CODE_TOO_SHORT);
        $this->generator->setMaxLength(2);
        $this->generator->setExistenceChecker($checker);
        $this->generator->generateIdentifier('Hello');
    }

    function testTooManyIterations()
    {
        $checker = new class implements TextIdentifierConflictCheckInterface
        {
            public function __invoke(string $identifier, array $context = []): bool{
                return true;
            }
        };

        $this->expectException(TextIdentifierGeneratorException::class);
        $this->expectExceptionCode(TextIdentifierGeneratorException::CODE_TOO_MANY_ITERATIONS);
        $this->generator->setMaxLength(TextIdentifierGenerator::UNLIMITED_LENGTH);
        $this->generator->setExistenceChecker($checker);
        $this->generator->setMaxIterations(10);
        $this->generator->generateIdentifier('Test');
    }
}
