<?php

namespace NtechServices\SubscriptionSystemAdmin\Resources\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \NtechServices\SubscriptionSystemAdmin\SubscriptionSystemAdmin
 */
class SubscriptionSystemAdmin extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \NtechServices\SubscriptionSystemAdmin\SubscriptionSystemAdmin::class;
    }
}
