<?php declare(strict_types=1);
namespace EPKTechnologies\EPKBundle\Tests\Utils;

use EPKTechnologies\EPKBundle\Utils\SlugGenerator;
use EPKTechnologies\EPKBundle\Utils\SlugGenerator\SlugConflictCheckInterface;
use EPKTechnologies\EPKBundle\Utils\SlugGenerator\SlugGeneratorException;
use PHPUnit\Framework\TestCase;

/**
 * @author Jan Egert <jan.egert@epk-technologies.com>
 */
class SlugGeneratorTest extends TestCase
{
    protected SlugGenerator $generator;

    protected function setUp(): void
    {
        $this->generator = new SlugGenerator();
    }


    function testGenerateSlug()
    {
        $phrase = 'Wôrķšƥáçè ~~sèťtïñğš~~';

        $this->assertEquals('workspace-settings', $this->generator->generateSlug($phrase));
        $this->generator->setMaxLength(10);
        $this->assertEquals('workspace', $this->generator->generateSlug($phrase));

        $checker = new class implements SlugConflictCheckInterface
        {
            public function __invoke(string $slug): bool{
                return $slug === 'workspace' || $slug === 'workspace-settings';
            }
        };

        $this->generator->setExistenceChecker($checker);
        $this->generator->setMaxLength(9);
        $this->assertEquals('workspac1', $this->generator->generateSlug($phrase));

        $this->generator->setMaxLength(SlugGenerator::UNLIMITED_LENGTH);
        $this->assertEquals('workspace-settings1', $this->generator->generateSlug($phrase));
    }


    function testEmptyText()
    {
        $this->expectException(SlugGeneratorException::class);
        $this->expectExceptionCode(SlugGeneratorException::CODE_TOO_SHORT);
        $this->generator->generateSlug('');
    }

    function testWrongFormatText()
    {
        $this->expectException(SlugGeneratorException::class);
        $this->expectExceptionCode(SlugGeneratorException::CODE_TOO_SHORT);
        $this->generator->generateSlug(' - ');
    }

    function testTooShortText()
    {
        $checker = new class implements SlugConflictCheckInterface
        {
            public function __invoke(string $slug): bool{
                return true;
            }
        };

        $this->expectException(SlugGeneratorException::class);
        $this->expectExceptionCode(SlugGeneratorException::CODE_TOO_SHORT);
        $this->generator->setMaxLength(2);
        $this->generator->setExistenceChecker($checker);
        $this->generator->generateSlug('Hello');
    }

    function testTooManyIterations()
    {
        $checker = new class implements SlugConflictCheckInterface
        {
            public function __invoke(string $slug): bool{
                return true;
            }
        };

        $this->expectException(SlugGeneratorException::class);
        $this->expectExceptionCode(SlugGeneratorException::CODE_TOO_MANY_ITERATIONS);
        $this->generator->setMaxLength(SlugGenerator::UNLIMITED_LENGTH);
        $this->generator->setExistenceChecker($checker);
        $this->generator->setMaxIterations(10);
        $this->generator->generateSlug('Test');
    }
}
