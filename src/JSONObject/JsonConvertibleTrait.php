<?php declare(strict_types=1);
namespace EPKTechnologies\EPKBundle\JSONObject;

trait JsonConvertibleTrait
{
    use JsonSerializableTrait;
    use JsonDeserializableTrait;
}