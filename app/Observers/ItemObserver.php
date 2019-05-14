<?php

namespace App\Observers;

use App\Item;

class ItemObserver
{
    /**
     * Handle the item "created" event.
     *
     * @param  \App\Item  $item
     * @return void
     */
    public function created(Item $item)
    {
        //
    }

    /**
     * Handle the item "updated" event.
     *
     * @param  \App\Item  $item
     * @return void
     */
    public function updated(Item $item)
    {
        //
    }

    /**
     * Handle the item "deleted" event.
     *
     * @param  \App\Item  $item
     * @return void
     */
    public function deleted(Item $item)
    {
        //
    }

    /**
     * Handle the item "restored" event.
     *
     * @param  \App\Item  $item
     * @return void
     */
    public function restored(Item $item)
    {
        //
    }

    /**
     * Handle the item "force deleted" event.
     *
     * @param  \App\Item  $item
     * @return void
     */
    public function forceDeleted(Item $item)
    {
        //
    }
}
