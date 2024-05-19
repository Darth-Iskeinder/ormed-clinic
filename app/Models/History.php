<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'staff_id',
        'services',
        'reject_services',
        'total_amount',
        'total_staff_revenue',
        'total_company_revenue',
        'paid',
        'paid_date',
        'completed',
        'completed_date',
        'refund',
        'refund_date'
    ];

    protected $casts = [
        'services' => 'array',
        'reject_services' => 'array'
    ];

    public static function boot()
    {
        parent::boot();

        static::saving(function($model) {
            $user = auth()->user();
            $total_revenue = 0;
            $total_staff_revenue = 0;

            foreach ($model->services as $service) {
                $item = json_decode($service['service'], true);
                $total_revenue += $item['price'] * (int) $service['count'];
                $payoutPercentage = isset($item['payout_percentage']) ? (int) $item['payout_percentage'] : 100;
                $total_staff_revenue += round(($item['price'] * (int) $service['count'] * $payoutPercentage) / 100);
            }

            $model->total_amount = $total_revenue;
            $model->total_staff_revenue = $total_staff_revenue;
            $model->total_company_revenue = $total_revenue - $total_staff_revenue;
            $model->staff_id = $model->staff_id ?? $user->id;
        });
    }

    public function customer()
    {
       return  $this->belongsTo(Customer::class);
    }

    public function service()
    {
        return  $this->belongsTo(Service::class);
    }

    public function user()
    {
        return  $this->belongsTo(User::class);
    }

    public function works()
    {
        return $this->hasMany(Work::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
