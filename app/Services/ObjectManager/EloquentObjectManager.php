<?php

declare(strict_types=1);

namespace App\Services\ObjectManager;

use Illuminate\Database\Eloquent\Model;

class EloquentObjectManager implements ObjectManagerInterface {
    public function save($object) {
        if (!$object instanceof  Model) {
            throw new \UnexpectedValueException('Expected $object to be of type Model. Got ' . get_class($object));
        }
        $object->save();
    }

    public function forceDelete($object)
    {
        if (!$object instanceof  Model) {
            throw new \UnexpectedValueException('Expected $object to be of type Model. Got ' . get_class($object));
        }
        $object->forceDelete();
    }
}
