<?php

namespace App\Observers;

use App\Models\Blacklist;
use Illuminate\Support\Facades\Cache;

class BlacklistObserver
{
    /**
     * Handle the Blacklist "created" event.
     */
    public function created(Blacklist $blacklist): void
    {
        Cache::forget('blacklist_items');
    }

    /**
     * Handle the Blacklist "updated" event.
     */
    public function updated(Blacklist $blacklist): void
    {
        //
        Cache::forget('blacklist_items');
    }

    /**
     * Handle the Blacklist "deleted" event.
     */
    public function deleted(Blacklist $blacklist): void
    {
        //
        Cache::forget('blacklist_items');
    }

    /**
     * Handle the Blacklist "restored" event.
     */
    public function restored(Blacklist $blacklist): void
    {
        //
        Cache::forget('blacklist_items');
    }

    /**
     * Handle the Blacklist "force deleted" event.
     */
    public function forceDeleted(Blacklist $blacklist): void
    {
        //
        Cache::forget('blacklist_items');
    }
}
