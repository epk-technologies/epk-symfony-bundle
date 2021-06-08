<?php declare(strict_types=1);
namespace EPKTechnologies\EPKBundle\Utils\TextIdentifierGenerator;

use RuntimeException;

/**
 * @author Jan Egert <jan.egert@epk-technologies.com>
 */
class TextIdentifierGeneratorException extends RuntimeException
{
    const CODE_TOO_SHORT = 100;
    const CODE_TOO_MANY_ITERATIONS = 200;
}
