<?php

namespace App\Models;

use App\Helpers\FileHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Vinkla\Hashids\Facades\Hashids;

class Package extends Model
{
    use HasFactory, SoftDeletes;

    CONST DIR_UPLOAD = '/app/public/images/packages/';

    CONST DIR_GET_LINK = '/images/packages/';

    protected $table = 'packages';

    protected $fillable = [
        'code',
        'name',
        'avatar',
        'price',
        'status',
        'desc',
        'source_id'
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

    public function getAvatarAttribute($value)
    {
        return !empty($value) ? FileHelper::getLink($value, self::DIR_GET_LINK) : '';
    }
}
