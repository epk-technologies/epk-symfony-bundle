<?php declare(strict_types=1);
namespace EPKTechnologies\EPKBundle\Utils\TextIdentifierGenerator;

/**
 * Checker for conflict in existence of identifier
 *
 * @author Jan Egert <jan.egert@epk-technologies.com>
 */
interface TextIdentifierConflictCheckInterface
{
    /**
     * Check if identifier exists.Returns TRUE if in conflict with another identifier, else FALSE
     * Context is passed from @see \EPKTechnologies\EPKBundle\Utils\TextIdentifierGenerator::generateIdentifier
     */
    public function __invoke(string $identifier, array $context = []): bool;
}
