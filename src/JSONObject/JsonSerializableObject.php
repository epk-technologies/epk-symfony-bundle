<?php declare(strict_types=1);
namespace EPKTechnologies\EPKBundle\JSONObject;

use JsonSerializable;

abstract class JsonSerializableObject implements JsonSerializable
{
    use JsonSerializableTrait;
}
