<?php

namespace NtechServices\SubscriptionSystemAdmin\Resources\Commands;

use Illuminate\Console\Command;

class SubscriptionSystemAdminCommand extends Command
{
    public $signature = 'subscription-system-admin';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
