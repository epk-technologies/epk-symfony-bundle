<?php declare(strict_types=1);
namespace EPKTechnologies\EPKBundle\Tests\JSONObject\TestModels;

use EPKTechnologies\EPKBundle\JSONObject\JsonConvertibleObject;

class ModelB extends JsonConvertibleObject
{
    public bool $bool = false;
    public int $int = 20;
    public float $float = 20.5;
    public string $string = "test B";
    public \DateTime $date;
    public \DateTime $when;

    public array $array = [1,2,3];

    function __construct()
    {
        $this->date = new \DateTime('2021-09-02');
        $this->when = new \DateTime('2020-01-28T16:22:37-07:00');
    }
}
