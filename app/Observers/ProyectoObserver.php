<?php

namespace App\Observers;

use App\Models\Proyecto;
use Carbon\Carbon;

class ProyectoObserver
{

    /**
     * Handle the Proyecto "created" event.
     */
    public function created(Proyecto $proyecto): void
    {
        //
    }

    /**
     * Handle the Proyecto "updated" event.
     */
    public function updated(Proyecto $proyecto): void
    {
        //
    }

    /**
     * Handle the Proyecto "deleted" event.
     */
    public function deleted(Proyecto $proyecto): void
    {
        //
    }

    /**
     * Handle the Proyecto "restored" event.
     */
    public function restored(Proyecto $proyecto): void
    {
        //
    }

    /**
     * Handle the Proyecto "force deleted" event.
     */
    public function forceDeleted(Proyecto $proyecto): void
    {
        //
    }
}
