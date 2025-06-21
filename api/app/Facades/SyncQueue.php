<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class SyncQueue extends Facade {
    protected static function getFacadeAccessor() {
        return 'SyncQueue';
    }
}