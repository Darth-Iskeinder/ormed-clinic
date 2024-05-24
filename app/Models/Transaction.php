<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'sum',
        'payment_type',
        'notes',
        'user_id',
        'history_id',
        'accept'
    ];

    public function history()
    {
        return $this->belongsTo(History::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $user = auth()->user();
            $model->user_id = $user->id;
        });

        static::created(function () {
            return redirect()->back();
        });
    }
}
