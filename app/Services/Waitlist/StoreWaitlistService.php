<?php

namespace App\Services\Waitlist;

use App\Models\Waitlist;

class StoreWaitlistService
{
    /**
     * Armazena uma nova inscrição na waitlist.
     */
    public function execute(array $data): Waitlist
    {
        $data['ip_address'] = request()->ip();
        $data['user_agent'] = request()->userAgent();

        return Waitlist::create($data);
    }
}
