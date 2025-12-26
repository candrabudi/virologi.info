<?php

namespace App\Observers;

use Illuminate\Support\Facades\Cache;

class GlobalLayoutObserver
{
    public function saved($model): void
    {
        Cache::forget('global_layout_data');
    }

    public function deleted($model): void
    {
        Cache::forget('global_layout_data');
    }
}
