<?php

declare(strict_types=1);

namespace App\Services\ObjectManager;

interface ObjectManagerInterface {
    public function save($object);
    public function forceDelete($object);
}
