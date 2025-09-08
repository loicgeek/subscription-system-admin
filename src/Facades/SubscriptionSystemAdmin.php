<?php

namespace NtechServices\SubscriptionSystemAdmin\Facades;

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
