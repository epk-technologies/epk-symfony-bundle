<?php declare(strict_types=1);
namespace EPKTechnologies\EPKBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author Jan Egert <jan.egert@epk-technologies.com>
 */
class EPKBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
