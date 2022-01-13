<?php

namespace App;

use App\Traits\UuidForKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    use UuidForKey;
    protected $fillable = ['folder', 'filename','extension','user_id','note_id','filesize'];

    protected static function booted()
    {
        static::deleting(function ($file) {

            Storage::delete(config('filesystems.file_dir').'/'.$file->filename);
        });
    }
    public function note(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Note::class);
    }

}
