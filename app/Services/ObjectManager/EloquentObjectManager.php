<?php

declare(strict_types=1);

namespace App\Services\ObjectManager;

use App\Exceptions\UnexpectedInstance;
use Illuminate\Database\Eloquent\Model;

/**
 * Class to put the actual database interaction into a injectable service.
 * This makes it possible to actually write unit tests without hitting the database.
 */
class EloquentObjectManager implements ObjectManagerInterface
{
    public function save($object)
    {
        if (!$object instanceof Model) {
            throw new UnexpectedInstance(Model::class, $object);
        }

        $object->save();
    }

    public function forceDelete($object)
    {
        if (!$object instanceof Model) {
            throw new UnexpectedInstance(Model::class, $object);
        }

        $object->forceDelete();
    }
}
