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
     * @return object[]
     */
    public static function associateObjects(iterable $objects, string $assoc_by_property): array
    {
        $output = [];
        $objects_class = null;
        $property_reflection = null;


        foreach($objects as $key => $object){

            if($objects_class === null){
                if(!is_object($object)){
                    throw new InvalidArgumentException("Item with key '{$key}' is not instance of object but " . gettype($object));
                }

                $objects_class = $object::class;

                try {

                    $property_reflection = new ReflectionProperty($objects_class, $assoc_by_property);
                    $property_reflection->setAccessible(true);

                } catch (ReflectionException){
                    throw new InvalidArgumentException("Class '{$objects_class}' does not have property '{$assoc_by_property}'");
                }

            } elseif($object::class !== $objects_class){
                throw new InvalidArgumentException("Invalid object class with key '{$key}'. {$objects_class} expected, ".$object::class." given");
            }

            if($property_reflection->isPublic()){
                $value = $object->{$assoc_by_property};
            } else {
                $value = $property_reflection->getValue($object);
            }

            if(!is_scalar($value)){
                if($value instanceof UuidInterface){
                    $value = $value->toString();
                } elseif($value instanceof DateTimeInterface) {
                    $value = $value->format(DATE_ATOM);
                } else {
                    $value = (string)$value;
                }
            }


            $output[$value] = $object;
        }

        return $output;
    }
}

