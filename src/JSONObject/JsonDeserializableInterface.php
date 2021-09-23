<?php declare(strict_types=1);
namespace EPKTechnologies\EPKBundle\JSONObject;

interface JsonDeserializableInterface
{
    public function jsonDeserialize(array $json_data);
    public static function createFromJSON(array $json_data): static;
}
