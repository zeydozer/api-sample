<?php

namespace App\Models;

use App\Models\Subscription;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'u_id', 'u_id');
    }

    public function subscription()
    {
        return $this->hasOne(Subscription::class, 'u_id', 'u_id')
            ->where('app_id', $this->app_id);
    }
}