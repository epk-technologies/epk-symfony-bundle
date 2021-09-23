<?php declare(strict_types=1);
namespace EPKTechnologies\EPKBundle\Tests\JSONObject;

use EPKTechnologies\EPKBundle\Tests\JSONObject\TestModels\ModelA;
use EPKTechnologies\EPKBundle\Tests\JSONObject\TestModels\ModelB;
use PHPUnit\Framework\TestCase;

/**
 * @author Jan Egert <jan.egert@epk-technologies.com>
 */
class JsonConvertibleObjectTest extends TestCase
{
    function testSerialize()
    {
        $model_b = [
            'bool' => false,
            'int' => 20,
            'float' => 20.5,
            'string' => 'test B',
            'date' => '2021-09-02',
            'when' => '2020-01-28T16:22:37-07:00',
            'array' => [1, 2, 3],
        ];

        $expected = [
            'bool' => true,
            'int' => 10,
            'float' => 10.5,
            'string' => 'test',
            'nullable' => null,
            'date' => '2021-09-01',
            'when' => '2020-01-28T16:22:37-07:00',
            'array' => [1, "test", true],
            "model_b" => $model_b,
            "models" => [$model_b, $model_b, $model_b]
        ];

        $a = new ModelA();
        $this->assertEquals($expected, $a->jsonSerialize());
        $this->assertEquals(json_encode($expected), json_encode($a->jsonSerialize()));
    }

    function testDeserialize()
    {
        $model_b = [
            'bool' => false,
            'int' => 30,
            'float' => 30.5,
            'string' => 'test BB',
            'date' => '2021-09-03',
            'when' => '2020-01-28T16:22:37-07:00',
            'array' => [1, 3],
        ];

        $json = [
            'bool' => false,
            'int' => 15,
            'float' => 15.5,
            'string' => 'test',
            'nullable' => null,
            'date' => '2021-10-01',
            'when' => '2020-02-28T16:22:37-07:00',
            'array' => [1, "test"],
            "model_b" => $model_b,
            "models" => [$model_b, $model_b]
        ];

        $b = new ModelB();
        $b->bool = false;
        $b->int = 30;
        $b->float = 30.5;
        $b->string = "test BB";
        $b->date = new \DateTime("2021-09-03");
        $b->when = new \DateTime("2020-01-28T16:22:37-07:00");
        $b->array = [1, 3];

        $a = new ModelA();
        $a->bool = false;
        $a->int = 15;
        $a->float = 15.5;
        $a->string = "test";
        $a->date = new \DateTime("2021-10-01");
        $a->when = new \DateTime("2020-02-28T16:22:37-07:00");
        $a->array = [1, "test"];
        $a->model_b = $b;
        $a->models = [$b, $b];

        $deserialized = ModelA::createFromJSON($json);
        $this->assertEquals($a, $deserialized);
    }
}