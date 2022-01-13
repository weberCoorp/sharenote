<?php

namespace App\Listeners;

use App\Events\NoteShared;
use App\Notifications\NoteSharedNotification;
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
        $event->user->notify( new NoteSharedNotification($event->note));
    }
}
