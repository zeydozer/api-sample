<?php

namespace App\Models;

use App\Events\Canceled;
use App\Events\Renewed;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Events\Started;

class Subscription extends Model
{
    use HasFactory;

    public static function boot()
    {
        parent::boot();

        static::updating(function ($item) {
            $item->updated_at_date = $item->updated_at->format('Y-m-d');
        });

        static::updated(function ($item) {
            if ($item->is_renewed)
                Renewed::dispatch($item);
            else if ($item->is_started)
                Started::dispatch($item);
            else if ($item->is_finished)
                Canceled::dispatch($item);
        });

        static::created(function ($item) {
            if ($item->is_renewed)
                Renewed::dispatch($item);
            else if ($item->is_started)
                Started::dispatch($item);
            else if ($item->is_finished)
                Canceled::dispatch($item);
        });
    }

    public function user()
    {
        return $this->hasOne(User::class, 'u_id', 'u_id')
            ->where('app_id', $this->app_id);
    }
}