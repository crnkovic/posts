<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait UsesUuid
{
    /**
     * Boot the trait by registering a event listener to generate unique ID for the model on creation.
     *
     * @return void
     */
    public static function bootUsesUuid() : void
    {
        static::creating(function (Model $model) {
            // Since we don't want `$fillable` array to grant access to `id` overrides,
            // we can force fill the ID field here to ensure UUID is properly generated...
            $model->forceFill([
                'id' => (string) Str::uuid(),
            ]);
        });
    }
}
