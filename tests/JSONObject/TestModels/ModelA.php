<?php declare(strict_types=1);
namespace EPKTechnologies\EPKBundle\Tests\JSONObject\TestModels;

use EPKTechnologies\EPKBundle\JSONObject\JsonConvertibleObject;

class ModelA extends JsonConvertibleObject
{
    public bool $bool = true;
    public int $int = 10;
    public float $float = 10.5;
    public string $string = "test";
    public ?string $nullable = null;
    public \DateTime $date;
    public \DateTime $when;
    public array $array = [1, "test", true];
    public ModelB $model_b;
    public array $models = [];

    function __construct()
    {
        $this->date = new \DateTime('2021-09-01');
        $this->when = new \DateTime('2020-01-28T16:22:37-07:00');
        $this->model_b = new ModelB();
        $this->models = [
            new ModelB(),
            new ModelB(),
            new ModelB(),
        ];
    }

    protected function jsonDeserializeProperty(string $property, $value)
    {
        return match ($property){
            'model_b' => ModelB::createFromJSON($value),
            'models' => array_map(fn(array $data) => ModelB::createFromJSON($data), $value),
            default => parent::jsonDeserializeProperty($property, $value)
        };
    }


}
