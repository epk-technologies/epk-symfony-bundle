<?php declare(strict_types=1);
namespace EPKTechnologies\EPKBundle\JSONObject;

abstract class JsonConvertibleObject extends JsonSerializableObject implements JsonConvertibleInterface
{
    use JsonDeserializableTrait;
}
