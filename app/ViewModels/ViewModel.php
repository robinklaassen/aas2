<?php

namespace App\ViewModels;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

abstract class ViewModel implements Jsonable, \JsonSerializable, Arrayable
{
    protected array $visible = [];

    public function __get(string $name)
    {
        if (!isset($name)) {
            throw new \BadMethodCallException('No getter method in view for ' . $name);
        }

        $methodName = self::getMethodName($name);
        if (method_exists($this, $methodName)) {
            return $this->$methodName();
        }

        throw new \RuntimeException('Unexpected code path for ' . $name);
    }

    public function __set(string $name, $value)
    {
        throw new \Exception('No setter allowed in view model');
    }

    public function __isset(string $name): bool
    {
        if (!contains($this->visible, $name)) {
            return false;
        }

        $methodName = self::getMethodName($name);
        if (method_exists($this, $methodName)) {
            return true;
        }

        return false;
    }

    public function __toString()
    {
        return $this->toJson();
    }

    public function toArray()
    {
        $attribute = [];
        foreach ($this->visible as $key) {
            $attribute[$key] = $this->$key;
        }

        return $attribute;
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }

    public function toJson($options = 0)
    {
        $json = json_encode($this->jsonSerialize(), $options);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw JsonEncodingException::forModel($this, json_last_error_msg());
        }

        return $json;
    }

    private static function getMethodName(string $property)
    {
        return 'get' . implode('', array_map('ucfirst', explode('_', $property)));
    }
}
