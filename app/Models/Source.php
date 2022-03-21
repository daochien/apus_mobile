<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Vinkla\Hashids\Facades\Hashids;

class Source extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'sources';

    protected $fillable = [
        'code',
        'name',
        'avatar',
        'path',
        'status',
        'desc'
    ];

    public $timestamps = true;

    protected static function booted()
    {
//        static::creating(function ($model) {
//            $model->code = Hashids::encode($model->id);
//        });
        static::created(function ($model) {
            $model->code = strtoupper(Hashids::encode($model->id));
            $model->save();
        });
    }

}
