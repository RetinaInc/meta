<?php

namespace Jasny\Meta;

/**
 * Cast class properties based on the meta data.
 * 
 * @author  Arnold Daniels <arnold@jasny.net>
 * @license https://raw.github.com/jasny/meta/master/LICENSE MIT
 * @link    https://jasny.github.com/meta
 */
trait TypeCasting
{
    /**
     * Cast properties
     * 
     * @return $this
     */
    public function cast()
    {
        foreach (static::meta()->ofProperties() as $name => $meta) {
            if (!isset($this->$name)) continue;
            
            if (isset($meta['var'])) {
                try {
                    $this->$name = static::castValue($this->$name, $meta['var']);
                } catch (\Exception $e) {
                    $desc = get_called_class() . (method_exists($this, '__toString') ? " '" . (string)$this . "'" : '');
                    trigger_error($e->getMessage() . " for property '$name' of $desc", E_USER_NOTICE);
                }
            }
        }
        
        return $this;
    }
    
    
    /**
     * Cast the value to a type.
     * 
     * @param mixed  $value
     * @param string $type
     * @return mixed
     */
    protected static function castValue($value, $type)
    {
        if ($type === 'bool') $type = 'boolean';
        if ($type === 'int') $type = 'integer';

        // Cast to empty array
        if (is_null($value) && $type === 'array') return [];
        
        // No casting needed
        if (is_null($value) || (is_object($value) && is_a($value, $type)) || gettype($value) === $type) {
            return $value;
        }
        
        // Cast internal types
        if (in_array($type, ['string', 'boolean', 'integer', 'float', 'array', 'object', 'resource'])) {
            return call_user_func([get_called_class(), 'castValueTo' . ucfirst($type)], $value);
        }

        // Cast to class
        return substr($type, -2) === '[]' ?
            static::castValueToArray($value, substr($type, 0, -2)) :
            static::castValueToClass($value, $type);
    }
    
    /**
     * Cast value to a string
     * 
     * @param mixed $value
     * @return string
     */
    protected static function castValueToString($value)
    {
        if ($value instanceof \DateTime) return $value->format('c');

        if (is_resource($value))
            throw new \Exception("Unable to cast a " . get_resource_type($value) . " resource to a string");
        
        if (is_array($value)) throw new \Exception("Unable to cast an array to a string");
        
        if (is_object($value) && !method_exists($value, '__toString'))
            throw new \Exception("Unable to cast a " . get_class($value).  " object to a string");
        
        return (string)$value;
    }
    
    /**
     * Cast value to a boolean
     * 
     * @param mixed $value
     * @return boolean
     */
    protected static function castValueToBoolean($value)
    {
        if (is_resource($value))
            throw new \Exception("Unable to cast a " . get_resource_type($value) . " resource to a boolean");
        
        if (is_object($value))
            throw new \Exception("Unable to cast a " . get_class($value) . " object to a boolean");
        
        if (is_array($value)) throw new \Exception("Unable to cast an array to a boolean");
        
        if (is_string($value)) {
            if (in_array(strtolower($value), ['1', 'true', 'yes', 'on'])) return true;
            if (in_array(strtolower($value), ['', '0', 'false', 'no', 'off'])) return false;
            
            throw new \Exception("Unable to cast string \"$value\" to a boolean");
        }
        
        return (bool)$value;
    }
    
    /**
     * Cast value to an integer
     * 
     * @param mixed $value
     * @return int
     */
    protected static function castValueToInteger($value)
    {
        return static::castValueToNumber('integer', $value);
    }
    
    /**
     * Cast value to an integer
     * 
     * @param mixed $value
     * @return int
     */
    protected static function castValueToFloat($value)
    {
        return static::castValueToNumber('float', $value);
    }
    
    /**
     * Cast value to an integer
     * 
     * @param string $type   'integer' or 'float'
     * @param mixed  $value
     * @return int|float
     */
    protected static function castValueToNumber($type, $value)
    {
        if (is_resource($value))
            throw new \Exception("Unable to cast a " . get_resource_type($value) . " resource to a $type");
        
        if (is_object($value))
            throw new \Exception("Unable to cast a " . get_class($value) . " object to a $type");
        
        if (is_array($value))
            throw new \Exception("Unable to cast an array to a $type");
        
        if (is_string($value)) {
            $value = trim($value);
            if (!is_numeric($value) && $value !== '')
                throw new \Exception("Unable to cast string \"$value\" to a $type");
        }
        
        settype($value, $type);
        return $value;
    }

    /**
     * Cast value to a typed array
     * 
     * @param mixed  $value
     * @param string $subtype  Type of the array items
     * @return mixed
     */
    protected static function castValueToArray($value, $subtype = null)
    {
        $array = $value === '' ? [] : (array)$value;

        if (isset($subtype)) {
            foreach ($array as &$v) {
                $v = static::castValue($v, $subtype);
            }
        }
        
        return $array;
    }
    
    /**
     * Cast value to an object
     * 
     * @param mixed $value
     * @return object
     */
    protected static function castValueToObject($value)
    {
        if (is_resource($value))
            throw new \Exception("Unable to cast a " . get_resource_type($value) . " resource to an object");
        
        if (is_scalar($value))
            throw new \Exception("Unable to cast a ". gettype($value) . " to an object.");
        
        return (object)$value;
    }
    
    /**
     * Cast value to an object
     * 
     * @param mixed $value
     * @return object
     */
    protected static function castValueToResource($value)
    {
        throw new \Exception("Unable to cast a ". gettype($value) . " to a resource.");
    }
    
    /**
     * Cast value to a non-internal type
     * 
     * @param mixed  $value
     * @param string $type
     * @return mixed
     */
    protected static function castValueToClass($value, $type)
    {
        if (!class_exists($type)) throw new \Exception("Unable to cast to invalid/unknown type '$type'");
        
        return new $type($value);
    }
}
