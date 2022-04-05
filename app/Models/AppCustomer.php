<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Vinkla\Hashids\Facades\Hashids;

class AppCustomer extends Model
{
    use HasFactory;

    protected $table = 'app_customers';

    protected $fillable = [
        'code',
        'customer_name',
        'customer_phone',
        'customer_email',
        'status',
        'package_id',
        'source_id',
        'expired',
        'configs',
        'path'
    ];

    public $timestamps = true;

    protected static function booted()
    {
        static::created(function ($model) {
            $model->code = strtoupper(Hashids::encode($model->id));
            $model->save();
        });
    }

}
