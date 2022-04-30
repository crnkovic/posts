<?php

namespace App\Models;

use App\Models\Concerns\UsesUuid;

use function Illuminate\Events\queueable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    use HasFactory, UsesUuid;

    public const DISK = 'public';

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['user_id', 'title', 'image_path'];

    /**
     * Perform any actions required after the model boots.
     *
     * @return void
     */
    protected static function booted() : void
    {
        static::deleted(queueable(function (self $post) : void {
            $post->purge();
        }));
    }

    /**
     * Get the user that authored the post.
     *
     * @return BelongsTo<User, Post>
     */
    public function author() : BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scope the query to only include posts older than X days.
     *
     * @param Builder $query
     * @param int $days
     * @return Builder
     */
    public function scopeOlderThan(Builder $query, int $days) : Builder
    {
        return $query->where('created_at', '<=', now()->subDays($days));
    }

    /**
     * Get the absolute URL to the post image.
     *
     * @return string
     */
    public function imageUrl() : string
    {
        return $this->image_path
                ? Storage::disk(static::DISK)->url($this->image_path)
                : '';
    }

    /**
     * Permanently delete the post's cover image from storage.
     *
     * @return void
     */
    public function purge() : void
    {
        if (! empty($this->image_path)) {
            Storage::disk(static::DISK)->delete($this->image_path);
        }
    }
}
