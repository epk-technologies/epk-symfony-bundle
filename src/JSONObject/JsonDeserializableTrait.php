<?php declare(strict_types=1);
namespace EPKTechnologies\EPKBundle\JSONObject;

use DateTime;
use ReflectionClass;

trait JsonDeserializableTrait
{
    public static function createFromJSON(array $json_data): static
    {
        $ref = new ReflectionClass(static::class);
        /** @var JsonDeserializableTrait $object */
        $object = $ref->newInstanceWithoutConstructor();
        $object->jsonDeserialize($json_data);
        return $object;
    }

    public function jsonDeserialize(array $json_data)
    {
        foreach($json_data as $property => $value){
            if(!property_exists($this, $property) || $property[0] === '_'){
                continue;
            }
            $this->{$property} = $this->jsonDeserializeProperty($property, $value);
        }
    }

    protected function jsonDeserializeProperty(string $property, $value)
    {
        if(preg_match('~_?(date|when|datetime)_?~', $property)){
            if(!$value){
                return null;
            }
            return new DateTime($value);
        }

        return $value;
    }
}
