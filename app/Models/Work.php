<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Work extends Model
{
    use HasFactory;

    protected $fillable = [
        'services',
        'user_id',
        'history_id',
        'total_amount',
        'total_staff_amount',
        'total_company_amount'
    ];

    protected $casts = [
        'services' => 'array'
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
            $totalRevenue = 0;
            $totalStaffRevenue = 0;
            foreach ($model->services as $service) {
                $item = json_decode($service['service'], true);
                $totalRevenue += $item['price'] * (int) $service['count'];
                $payoutPercentage = isset($item['payout_percentage']) ? (int) $item['payout_percentage'] : 0;
                $totalStaffRevenue = round(($item['price'] * (int) $service['count'] * $payoutPercentage) / 100);
            }

            $model->total_amount = $totalRevenue;
            $model->total_staff_amount = $totalStaffRevenue;
            $model->total_company_amount = $totalRevenue - $totalStaffRevenue;
            $model->user_id = $user->id;
        });

        static::created(function () {
            return redirect()->back();
        });
    }
}
