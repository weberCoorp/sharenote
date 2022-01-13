<?php


namespace App\Traits;


use Illuminate\Support\Str;

trait UuidForKey
{
    /**
     * Used by Eloquent to get primary key type.
     * UUID Identified as a string.
     * @return string
     */

    public function getKeyType(): string
    {
        return 'string';
    }

    /**
     * Used by Eloquent to get if the primary key is auto increment value.
     * UUID is not.
     * @return bool
     */
    public function getIncrementing(): bool
    {
        return false;
    }

    /**
     * Add behavior to creating and saving Eloquent events.
     * @return void
     */
    protected static function boot() {

        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = Str::uuid()->toString();
            }
        });
    }

}
