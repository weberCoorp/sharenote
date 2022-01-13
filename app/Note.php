<?php

namespace App;


use App\Traits\UuidForKey;
use Illuminate\Database\Eloquent\Model;


class Note extends Model
{
    use UuidForKey;

    protected $fillable = ['description','user_id', 'name', 'private'];

    protected static function booted()
    {
        static::deleted(function ($note) {
            foreach($note->files as $file){
                $file->delete();
            }
        });
    }


    public function scopePrivate($query)
    {
      return $query->where('private',1);
    }

    public function scopePublic($query)
    {
        return $query->where('private',0);
    }

    /**
     * list of users who has access to this note
     */
    public function usersHasAccess(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function files()
    {
        return $this->hasMany(File::class);
    }
}
