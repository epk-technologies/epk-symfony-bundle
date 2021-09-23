<?php declare(strict_types=1);
namespace EPKTechnologies\EPKBundle\JSONObject;

use DateTime;
use JsonSerializable;

trait JsonSerializableTrait
{
    /**
     * {@inheritdoc}
     */
    public function jsonSerialize(): array
    {
        $output = [];
        foreach(get_object_vars($this) as $property => $value){
            if($property[0] === '_'){
                continue;
            }
            $output[$property] = $this->jsonSerializeProperty($property, $value);
        }
        return $output;
    }

    protected function jsonSerializeProperty(string $property, $value)
    {
        if(is_scalar($value) || $value === null){
            return $value;
        }

        if($value instanceof JsonSerializable){
            return $value->jsonSerialize();
        }

        if($value instanceof DateTime){
            if(preg_match('~_?date_?~', $property)){
                return $value->format('Y-m-d');
            }
            return $value->format(DATE_ATOM);
        }

        if(is_iterable($value)){
            $output = [];
            foreach($value as $k => $v){
                $output[$k] = $this->jsonSerializeProperty("{$property}|{$k}", $v);
            }
            return $output;
        }

        if(is_object($value)){
            $output = [];
            foreach(get_object_vars($value) as $k => $v){
                if($k[0] === '_'){
                    continue;
                }
                $output[$k] = $this->jsonSerializeProperty("{$property}|{$k}", $v);
            }
            return $output;
        }

        return $value;
    }
}
