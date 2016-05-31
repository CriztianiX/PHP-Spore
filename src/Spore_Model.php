<?php
namespace PHP_Spore;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\DocParser;
use ReflectionClass;

class Spore_Model
{
    private $class;

    public function __construct($class)
    {
        $this->class = $class;
    }

    protected function getReader(DocParser $parser = null)
    {
        return new AnnotationReader($parser);
    }

    public function getProperties()
    {
        return array_keys(get_class_vars($this->class));
    }

    public function hydrateFrom($array)
    {
        $properties = $this->getProperties();

        $foundProperties = array_filter($array, function ($key) use ($properties) {
            return in_array($key, $properties);
        }, ARRAY_FILTER_USE_KEY);

        $propertiesData = $this->collectPropertyData($foundProperties);

        $instance = new $this->class();

        foreach ($propertiesData as $data)
        {
            $key = $data['name'];
            $value = ModelStrategy::fromClassName($data['class'])->hydrateValue($data['value']);

            $instance->$key = $value;
        }

        return $instance;
    }

    private function collectPropertyData(array $foundProperties)
    {
        $reader = $this->getReader();
        $ref = new ReflectionClass($this->class);

        $resultProperties = [];
        foreach ($foundProperties as $key => $value) {
            $refProp = $ref->getProperty($key);
            $annotation = $reader->getPropertyAnnotation($refProp, Spore_Property::class);
            $resultProperties[] = [
                "name" => $key,
                "value" => $value,
                "class" => $annotation ? $annotation->class : null
            ];
        }

        return $resultProperties;
    }
}

class ModelStrategy {

    private function __construct(string $className = "")
    {
        $this->className = $className;
    }

    public function getModel()
    {
        return new Spore_Model($this->className);
    }

    public function hydrateValue($value)
    {
        return $this->getModel()->hydrateFrom($value);
    }

    public static function fromClassName($className)
    {
        if (!$className) return new DumbStrategy();
        return self::isArray($className) ? new ArrayModelStrategy($className) : new ModelStrategy($className);
    }

    public static function isArray($class)
    {
        return substr($class, -2) == "[]";
    }
}

class DumbStrategy extends ModelStrategy
{
    public function hydrateValue($value)
    {
        return $value;
    }
}

class ArrayModelStrategy extends ModelStrategy
{
    public function getModel()
    {
        $className = $this->className;
        return new Spore_Model(substr($className, 0, strlen($className) - 2));
    }

    public function hydrateValue($value)
    {
        $model = $this->getModel();
        return array_map(function ($val) use ($model) {
            return $model->hydrateFrom($val);
        }, $value);
    }
}