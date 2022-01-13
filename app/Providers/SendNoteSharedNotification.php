<?php

namespace App\Providers;

use App\Providers\NoteShared;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendNoteSharedNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  NoteShared  $event
     * @return void
     */
    public function handle(NoteShared $event)
    {
        //
    }
}
