<?php

namespace App\Observers;

use App\Models\PropertyRequest;
use App\Services\RequestNotificationService;

class PropertyRequestObserver
{
    public function created(PropertyRequest $request): void
    {
        app(RequestNotificationService::class)->created($request);
    }

    public function updated(PropertyRequest $request): void
    {
        app(RequestNotificationService::class)->updated($request);
    }
}
