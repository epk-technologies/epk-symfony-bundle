<?php declare(strict_types=1);
namespace EPKTechnologies\EPKBundle\Utils\SlugGenerator;

/**
 * Checker for conflict in existence of slug
 *
 * @author Jan Egert <jan.egert@epk-technologies.com>
 */
interface SlugConflictCheckInterface
{
    /**
     * Check if slug exists.Returns TRUE if in conflict with another slug, else FALSE
     */
    public function __invoke(string $slug, array $context = []): bool;
}
