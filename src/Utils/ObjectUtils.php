<?php declare(strict_types=1);
namespace EPKTechnologies\EPKBundle\Utils;

use DateTimeInterface;
use InvalidArgumentException;
use Ramsey\Uuid\UuidInterface;
use ReflectionException;
use ReflectionProperty;
use function is_object;

/**
 * Various utilities for working with objects
 *
 * @author Jan Egert <jan.egert@epk-technologies.com>
 */
class ObjectUtils
{
    /**
     * Associate objects by property value
     *
     * @param object[] $objects
     * @param string $assoc_by_property
     * @param callable|null $property_transformer function($raw_value, $original_key)
     * @return object[]
     */
    public static function associateObjects(iterable $objects, string $assoc_by_property, callable $property_transformer = null): array
    {
        $output = [];
        /** @var ReflectionProperty[] $property_reflections */
        $property_reflections = [];

        foreach($objects as $key => $object){

            if(!is_object($object)){
                throw new InvalidArgumentException("Item with key '{$key}' is not instance of object but " . gettype($object));
            }

            $class = get_class($object);
            if(!isset($property_reflections[$class])){
                try {

                    $property_reflections[$class] = new ReflectionProperty($class, $assoc_by_property);
                    $property_reflections[$class]->setAccessible(true);

                } catch (ReflectionException){
                    throw new InvalidArgumentException("Class '{$class}' does not have property '{$assoc_by_property}'");
                }
            }

            $property_reflection = $property_reflections[$class];

            if($property_reflection->isPublic()){
                $raw_value = $object->{$assoc_by_property};
            } else {
                $raw_value = $property_reflection->getValue($object);
            }

            if($property_transformer) {
                $value = $property_transformer($raw_value, $key);
            } elseif(!is_scalar($raw_value)) {
                if($raw_value instanceof UuidInterface){
                    $value = $raw_value->toString();
                } elseif($raw_value instanceof DateTimeInterface) {
                    $value = $raw_value->format(DATE_ATOM);
                } else {
                    $value = (string)$raw_value;
                }
            } else {
                $value = $raw_value;
            }

            $output[$value] = $object;
        }

        return $output;
    }
}

